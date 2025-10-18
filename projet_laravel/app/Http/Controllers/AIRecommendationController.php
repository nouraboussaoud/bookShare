<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use App\Models\User;

class AIRecommendationController extends Controller
{
    /**
     * Recommandations intelligentes de livres pour échange
     * Utilise l'IA pour analyser la similarité sémantique
     */
    public function recommendBooks(Request $request, $book)
    {
        try {
            $bookId = $book; // ID venant de la route
            $userId = $request->input('user_id', auth()->id());
            
            if (empty($bookId)) {
                return response()->json([
                    'error' => 'ID du livre requis',
                    'success' => false
                ], 400);
            }

            // Récupérer le livre de référence
            $sourceBook = Book::with('category')->find($bookId);
            if (!$sourceBook) {
                return response()->json([
                    'error' => 'Livre introuvable',
                    'success' => false
                ], 404);
            }

            // Configuration de l'IA
            $apiKey = env('HUGGINGFACE_API_KEY');
            $modelUrl = env('HUGGINGFACE_API_URL') . '/sentence-transformers/all-MiniLM-L6-v2';
            
            $startTime = microtime(true);
            Log::info('Starting AI book recommendations', [
                'source_book_id' => $bookId,
                'source_book_title' => $sourceBook->title
            ]);

            // Créer le texte de référence pour l'IA
            $sourceText = $this->createBookDescription($sourceBook);
            
            // Récupérer les livres candidats (excluant le livre source et les livres du même utilisateur)
            $candidateBooks = Book::with(['category', 'user'])
                ->where('id', '!=', $bookId)
                ->where('user_id', '!=', $userId)
                ->where('status', 'AVAILABLE')
                ->limit(10) // Réduire de 20 à 10 pour être plus rapide
                ->get();

            if ($candidateBooks->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'recommendations' => [],
                    'message' => 'Aucun livre disponible pour échange'
                ]);
            }

            // Optimisation : Pré-filtrage rapide avant l'IA
            $quickFiltered = $this->quickPreFilter($sourceBook, $candidateBooks);
            
            // Analyser seulement les 5 meilleurs candidats avec l'IA
            $recommendations = [];
            $maxAIAnalysis = 5; // Limiter les appels IA à 5 max
            $analyzed = 0;
            
            foreach ($quickFiltered as $candidateBook) {
                if ($analyzed >= $maxAIAnalysis) {
                    // Pour les livres restants, utiliser seulement le score local
                    $compatibilityScore = $this->calculateLocalCompatibilityScore($sourceBook, $candidateBook);
                    
                    $recommendations[] = [
                        'book' => [
                            'id' => $candidateBook->id,
                            'title' => $candidateBook->title,
                            'author' => $candidateBook->author,
                            'category' => $candidateBook->category->name ?? 'Non catégorisé',
                            'photo_url' => $candidateBook->photo_url,
                            'user' => [
                                'name' => $candidateBook->user->name
                            ]
                        ],
                        'similarity_score' => $compatibilityScore * 0.7, // Score estimé
                        'compatibility_score' => $compatibilityScore,
                        'reasons' => $this->generateLocalReasons($sourceBook, $candidateBook),
                        'exchange_potential' => $this->assessExchangePotential($compatibilityScore),
                        'ai_analyzed' => false
                    ];
                    continue;
                }
                
                $candidateText = $this->createBookDescription($candidateBook);
                
                // Calcul de similarité avec l'IA (seulement pour les top 5)
                $similarity = $this->calculateSimilarity($apiKey, $modelUrl, $sourceText, $candidateText);
                
                if ($similarity !== null) {
                    $compatibilityScore = $this->calculateCompatibilityScore($sourceBook, $candidateBook, $similarity);
                    
                    $recommendations[] = [
                        'book' => [
                            'id' => $candidateBook->id,
                            'title' => $candidateBook->title,
                            'author' => $candidateBook->author,
                            'category' => $candidateBook->category->name ?? 'Non catégorisé',
                            'photo_url' => $candidateBook->photo_url,
                            'user' => [
                                'name' => $candidateBook->user->name
                            ]
                        ],
                        'similarity_score' => round($similarity * 100, 1),
                        'compatibility_score' => $compatibilityScore,
                        'reasons' => $this->generateRecommendationReasons($sourceBook, $candidateBook, $similarity),
                        'exchange_potential' => $this->assessExchangePotential($compatibilityScore),
                        'ai_analyzed' => true
                    ];
                    
                    $analyzed++; // Incrémenter le compteur
                }
            }

            // Trier par score de compatibilité décroissant
            usort($recommendations, function($a, $b) {
                return $b['compatibility_score'] <=> $a['compatibility_score'];
            });

            // Garder les 5 meilleures recommandations
            $topRecommendations = array_slice($recommendations, 0, 5);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('AI recommendations completed', [
                'processing_time_ms' => $responseTime,
                'recommendations_count' => count($topRecommendations)
            ]);

            return response()->json([
                'success' => true,
                'source_book' => [
                    'id' => $sourceBook->id,
                    'title' => $sourceBook->title,
                    'author' => $sourceBook->author
                ],
                'recommendations' => $topRecommendations,
                'ai_provider' => 'Intelligence Artificielle Avancée',
                'processing_time' => $responseTime,
                'total_analyzed' => count($candidateBooks)
            ]);

        } catch (\Exception $e) {
            Log::error('AI Recommendation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erreur lors du calcul des recommandations',
                'success' => false,
                'fallback_recommendations' => $this->getFallbackRecommendations($bookId, $userId)
            ], 500);
        }
    }

    /**
     * Créer une description textuelle du livre pour l'IA
     */
    private function createBookDescription($book)
    {
        $description = $book->title . ' par ' . $book->author;
        
        if ($book->category) {
            $description .= ' dans la catégorie ' . $book->category->name;
        }
        
        if ($book->description) {
            $description .= '. ' . $book->description;
        }
        
        return $description;
    }

    /**
     * Calculer la similarité sémantique entre deux textes avec l'IA
     */
    private function calculateSimilarity($apiKey, $modelUrl, $text1, $text2)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->timeout(10)->post($modelUrl, [
                'inputs' => [
                    'source_sentence' => $text1,
                    'sentences' => [$text2]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result[0] ?? 0; // Score de similarité entre 0 et 1
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('Similarity calculation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Calculer un score de compatibilité global
     */
    private function calculateCompatibilityScore($sourceBook, $candidateBook, $aiSimilarity)
    {
        $score = 0;
        
        // Score IA (40% du total)
        $score += $aiSimilarity * 40;
        
        // Bonus catégorie identique (20%)
        if ($sourceBook->category_id && $sourceBook->category_id === $candidateBook->category_id) {
            $score += 20;
        }
        
        // Bonus âge compatible (20%)
        $ageDiff = abs($sourceBook->recommended_age - $candidateBook->recommended_age);
        if ($ageDiff <= 3) {
            $score += 20 - ($ageDiff * 3);
        }
        
        // Bonus auteur similaire (10%)
        if (stripos($sourceBook->author, $candidateBook->author) !== false || 
            stripos($candidateBook->author, $sourceBook->author) !== false) {
            $score += 10;
        }
        
        // Bonus mots-clés dans le titre (10%)
        $sourceWords = explode(' ', strtolower($sourceBook->title));
        $candidateWords = explode(' ', strtolower($candidateBook->title));
        $commonWords = array_intersect($sourceWords, $candidateWords);
        if (count($commonWords) > 0) {
            $score += min(count($commonWords) * 2, 10);
        }
        
        return min(round($score, 1), 100); // Maximum 100
    }

    /**
     * Générer les raisons de la recommandation
     */
    private function generateRecommendationReasons($sourceBook, $candidateBook, $similarity)
    {
        $reasons = [];
        
        if ($similarity > 0.7) {
            $reasons[] = "Contenu très similaire";
        } elseif ($similarity > 0.5) {
            $reasons[] = "Contenu similaire";
        } else {
            $reasons[] = "Analyse IA";
        }
        
        if ($sourceBook->category_id === $candidateBook->category_id) {
            $reasons[] = "Même catégorie";
        }
        
        if ($sourceBook->author === $candidateBook->author) {
            $reasons[] = "Même auteur";
        }
        
        $ageDiff = abs(($sourceBook->recommended_age ?? 12) - ($candidateBook->recommended_age ?? 12));
        if ($ageDiff <= 1) {
            $reasons[] = "Âge compatible";
        }
        
        return array_slice($reasons, 0, 3); // Maximum 3 raisons
    }

    /**
     * Évaluer le potentiel d'échange
     */
    private function assessExchangePotential($score)
    {
        if ($score >= 80) return 'Excellent match';
        if ($score >= 65) return 'Très bon match';
        if ($score >= 50) return 'Bon match';
        if ($score >= 35) return 'Match correct';
        return 'Match faible';
    }

    /**
     * Recommandations de secours (sans IA)
     */
    private function getFallbackRecommendations($bookId, $userId)
    {
        $sourceBook = Book::find($bookId);
        if (!$sourceBook) return [];

        return Book::with(['category', 'user'])
            ->where('id', '!=', $bookId)
            ->where('user_id', '!=', $userId)
            ->where('status', 'AVAILABLE')
            ->when($sourceBook->category_id, function ($query) use ($sourceBook) {
                return $query->where('category_id', $sourceBook->category_id);
            })
            ->limit(3)
            ->get()
            ->map(function ($book) {
                return [
                    'book_id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'category' => $book->category->name ?? 'Non catégorisé',
                    'owner_name' => $book->user->name,
                    'compatibility_score' => 50,
                    'recommendation_reason' => 'Recommandation basique par catégorie',
                    'exchange_potential' => 'Match correct'
                ];
            })
            ->toArray();
    }
    
    /**
     * Pré-filtrage rapide sans IA pour optimiser les performances
     */
    private function quickPreFilter($sourceBook, $candidateBooks)
    {
        return $candidateBooks->sortByDesc(function ($candidateBook) use ($sourceBook) {
            $score = 0;
            
            // Même catégorie = +30 points
            if ($sourceBook->category_id === $candidateBook->category_id) {
                $score += 30;
            }
            
            // Même auteur = +20 points
            if ($sourceBook->author === $candidateBook->author) {
                $score += 20;
            }
            
            // Âge compatible = +15 points
            $ageDiff = abs(($sourceBook->recommended_age ?? 12) - ($candidateBook->recommended_age ?? 12));
            if ($ageDiff <= 2) {
                $score += 15;
            }
            
            // Mots-clés dans le titre = +10 points
            $sourceWords = explode(' ', strtolower($sourceBook->title));
            $candidateWords = explode(' ', strtolower($candidateBook->title));
            $commonWords = array_intersect($sourceWords, $candidateWords);
            $score += count($commonWords) * 5;
            
            return $score;
        });
    }
    
    /**
     * Calcul de compatibilité local (sans IA) pour les livres non prioritaires
     */
    private function calculateLocalCompatibilityScore($sourceBook, $candidateBook)
    {
        $score = 30; // Score de base
        
        // Même catégorie (25%)
        if ($sourceBook->category_id === $candidateBook->category_id) {
            $score += 25;
        }
        
        // Même auteur (15%)
        if ($sourceBook->author === $candidateBook->author) {
            $score += 15;
        }
        
        // Âge compatible (15%)
        $ageDiff = abs(($sourceBook->recommended_age ?? 12) - ($candidateBook->recommended_age ?? 12));
        if ($ageDiff <= 1) {
            $score += 15;
        } elseif ($ageDiff <= 3) {
            $score += 10;
        }
        
        // Mots-clés communs (15%)
        $sourceWords = explode(' ', strtolower($sourceBook->title));
        $candidateWords = explode(' ', strtolower($candidateBook->title));
        $commonWords = array_intersect($sourceWords, $candidateWords);
        $score += min(count($commonWords) * 3, 15);
        
        return min($score, 95); // Maximum 95% pour score local
    }
    
    /**
     * Générer des raisons sans IA (pour optimisation)
     */
    private function generateLocalReasons($sourceBook, $candidateBook)
    {
        $reasons = [];
        
        if ($sourceBook->category_id === $candidateBook->category_id) {
            $reasons[] = "Même catégorie";
        }
        
        if ($sourceBook->author === $candidateBook->author) {
            $reasons[] = "Même auteur";
        }
        
        $ageDiff = abs(($sourceBook->recommended_age ?? 12) - ($candidateBook->recommended_age ?? 12));
        if ($ageDiff <= 2) {
            $reasons[] = "Âge compatible";
        }
        
        $sourceWords = explode(' ', strtolower($sourceBook->title));
        $candidateWords = explode(' ', strtolower($candidateBook->title));
        $commonWords = array_intersect($sourceWords, $candidateWords);
        if (count($commonWords) > 0) {
            $reasons[] = "Mots-clés similaires";
        }
        
        return empty($reasons) ? ["Sélection algorithmique"] : array_slice($reasons, 0, 3);
    }
}