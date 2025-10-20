<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookSummaryService
{
    protected $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    protected $apiKey;
    protected $model = 'llama-3.1-8b-instant';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
    }

    /**
     * Generate an AI summary for a book
     */
    public function generateSummary(Book $book): array
    {
        try {
            // Build the prompt for AI
            $prompt = $this->buildPrompt($book);

            // Call Groq API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un expert littéraire qui crée des résumés captivants de livres. Tes résumés sont concis, informatifs et donnent envie de lire le livre sans révéler les éléments clés de l\'intrigue.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 300,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $summary = $data['choices'][0]['message']['content'] ?? null;

                if ($summary) {
                    // Save summary to database
                    $book->update(['ai_summary' => trim($summary)]);

                    return [
                        'success' => true,
                        'summary' => trim($summary),
                        'message' => 'Résumé généré avec succès!',
                    ];
                }
            }

            Log::error('Book summary generation failed: ' . $response->body());

            return [
                'success' => false,
                'message' => 'Impossible de générer le résumé. Veuillez réessayer.',
            ];

        } catch (\Exception $e) {
            Log::error('Book summary error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la génération du résumé: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate summaries for multiple books
     */
    public function generateBulkSummaries(array $bookIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($bookIds as $bookId) {
            $book = Book::find($bookId);
            
            if (!$book) {
                $results['failed']++;
                continue;
            }

            $result = $this->generateSummary($book);

            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
            }

            $results['details'][$bookId] = $result;

            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 second
        }

        return $results;
    }

    /**
     * Build prompt for AI based on book information
     */
    private function buildPrompt(Book $book): string
    {
        $prompt = "Génère un résumé captivant et professionnel pour ce livre:\n\n";
        $prompt .= "📚 Titre: {$book->title}\n";
        $prompt .= "✍️ Auteur: {$book->author}\n";

        if ($book->category) {
            $prompt .= "🏷️ Catégorie: {$book->category->name}\n";
        }

        if ($book->description) {
            $prompt .= "📝 Description existante: {$book->description}\n";
        }

        $prompt .= "\nCrée un résumé en français qui:\n";
        $prompt .= "- Fait 3-4 phrases maximum\n";
        $prompt .= "- Présente le thème principal du livre\n";
        $prompt .= "- Donne envie de lire sans spoiler\n";
        $prompt .= "- Est engageant et professionnel\n";
        $prompt .= "- Utilise un ton adapté à la catégorie du livre\n";

        if (!$book->description) {
            $prompt .= "\nNote: Comme il n'y a pas de description, base-toi sur le titre, l'auteur et la catégorie pour créer un résumé pertinent et attractif.\n";
        }

        return $prompt;
    }

    /**
     * Generate a short pitch (one-liner) for a book
     */
    public function generatePitch(Book $book): array
    {
        try {
            $prompt = "Crée une phrase d'accroche captivante (maximum 20 mots) pour ce livre:\n\n";
            $prompt .= "Titre: {$book->title}\n";
            $prompt .= "Auteur: {$book->author}\n";
            
            if ($book->category) {
                $prompt .= "Catégorie: {$book->category->name}\n";
            }

            $prompt .= "\nLa phrase doit être percutante et donner envie de lire le livre.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(20)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un expert en marketing littéraire qui crée des phrases d\'accroche percutantes.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.8,
                'max_tokens' => 50,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $pitch = $data['choices'][0]['message']['content'] ?? null;

                if ($pitch) {
                    return [
                        'success' => true,
                        'pitch' => trim($pitch),
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Impossible de générer la phrase d\'accroche.',
            ];

        } catch (\Exception $e) {
            Log::error('Pitch generation error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Improve existing description with AI
     */
    public function improveDescription(Book $book): array
    {
        if (!$book->description) {
            return [
                'success' => false,
                'message' => 'Aucune description à améliorer.',
            ];
        }

        try {
            $prompt = "Améliore cette description de livre pour la rendre plus engageante et professionnelle:\n\n";
            $prompt .= "Titre: {$book->title}\n";
            $prompt .= "Auteur: {$book->author}\n";
            $prompt .= "Description actuelle: {$book->description}\n\n";
            $prompt .= "Réécris la description en la rendant plus captivante, claire et concise (3-4 phrases).";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un éditeur professionnel qui améliore les descriptions de livres.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 300,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $improved = $data['choices'][0]['message']['content'] ?? null;

                if ($improved) {
                    return [
                        'success' => true,
                        'improved_description' => trim($improved),
                        'original_description' => $book->description,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Impossible d\'améliorer la description.',
            ];

        } catch (\Exception $e) {
            Log::error('Description improvement error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ];
        }
    }
}
