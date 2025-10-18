<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIReportController extends Controller
{
    /**
     * Classification automatique des signalements avec Hugging Face
     */
    public function classifyReport(Request $request)
    {
        try {
            $description = $request->input('description');
            
            if (empty($description)) {
                return response()->json([
                    'error' => 'Description requise',
                    'success' => false
                ], 400);
            }

            // Configuration du modèle Hugging Face
            $apiKey = env('HUGGINGFACE_API_KEY');
            $modelUrl = env('HUGGINGFACE_API_URL') . '/facebook/bart-large-mnli';
            
            // Labels de classification pour les signalements (simplifiés)
            $candidateLabels = [
                'problème avec un livre non rendu',
                'comportement inapproprié',
                'problème technique'
            ];

            // Appel à l'API Hugging Face - Classification Zero-shot
            $startTime = microtime(true);
            Log::info('Starting AI classification', ['description_length' => strlen($description)]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->timeout(8)->post($modelUrl, [
                'inputs' => $description,
                'parameters' => [ 
                    'candidate_labels' => $candidateLabels
                ]
            ]);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('AI API response received', [
                'response_time_ms' => $responseTime,
                'status_code' => $response->status()
            ]);

            if ($response->failed()) {
                Log::error('Hugging Face API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'error' => 'Erreur du service IA',
                    'success' => false
                ], 500);
            }

            $result = $response->json();
            
            // Mapping des résultats vers nos types de signalement (simplifié)
            $typeMapping = [
                'problème avec un livre non rendu' => 'CONFLIT_ECHANGE',
                'comportement inapproprié' => 'COMPORTEMENT',
                'problème technique' => 'AUTRE'
            ];

            $bestLabel = $result['labels'][0] ?? '';
            $confidence = $result['scores'][0] ?? 0;
            $suggestedType = $typeMapping[$bestLabel] ?? 'AUTRE';

            // Détermine si la suggestion est assez fiable (seuil abaissé)
            $isConfident = $confidence > 0.55;
            
            // Génère une explication pour l'utilisateur
            $explanation = $this->generateExplanation($bestLabel, $confidence, $description);

            return response()->json([
                'success' => true,
                'suggested_type' => $suggestedType,
                'confidence' => round($confidence * 100, 1),
                'is_confident' => $isConfident,
                'explanation' => $explanation,
                'all_predictions' => array_combine($result['labels'], $result['scores']),
                'ai_provider' => 'Intelligence Artificielle Avancée',
                'processing_time' => $responseTime
            ]);

        } catch (\Exception $e) {
            Log::error('AI Classification Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erreur lors de la classification IA',
                'success' => false,
                'fallback_suggestion' => $this->getFallbackClassification($request->input('description'))
            ], 500);
        }
    }

    /**
     * Génère une explication lisible pour l'utilisateur
     */
    private function generateExplanation($label, $confidence, $description)
    {
        $confidencePercent = round($confidence * 100, 1);
        
        $explanations = [
            'conflit d\'échange de livre' => "L'IA a détecté des termes liés aux échanges de livres (confiance: {$confidencePercent}%)",
            'comportement inapproprié ou harcèlement' => "L'IA a identifié un problème de comportement (confiance: {$confidencePercent}%)",
            'problème technique ou autre' => "L'IA classe ceci comme un problème technique ou autre nature (confiance: {$confidencePercent}%)"
        ];

        return $explanations[$label] ?? "Classification IA avec {$confidencePercent}% de confiance";
    }

    /**
     * Classification de secours basique si l'IA échoue
     */
    private function getFallbackClassification($description)
    {
        $description = strtolower($description ?? '');
        
        // Mots-clés simples pour classification de secours
        $exchangeKeywords = ['livre', 'échange', 'prêt', 'rendu', 'retour', 'récupérer'];
        $behaviorKeywords = ['insulte', 'agressif', 'impoli', 'harcèlement', 'comportement'];
        
        $exchangeScore = 0;
        $behaviorScore = 0;
        
        foreach ($exchangeKeywords as $keyword) {
            if (strpos($description, $keyword) !== false) {
                $exchangeScore++;
            }
        }
        
        foreach ($behaviorKeywords as $keyword) {
            if (strpos($description, $keyword) !== false) {
                $behaviorScore++;
            }
        }
        
        if ($exchangeScore > $behaviorScore) {
            return ['type' => 'CONFLIT_ECHANGE', 'method' => 'fallback'];
        } elseif ($behaviorScore > 0) {
            return ['type' => 'COMPORTEMENT', 'method' => 'fallback'];
        } else {
            return ['type' => 'AUTRE', 'method' => 'fallback'];
        }
    }

    /**
     * Test de connexion à l'API Hugging Face
     */
    public function testConnection()
    {
        try {
            $apiKey = env('HUGGINGFACE_API_KEY');
            
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service IA non configuré'
                ]);
            }

            // Test simple avec le modèle
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(10)->post(env('HUGGINGFACE_API_URL') . '/facebook/bart-large-mnli', [
                'inputs' => 'test de connexion',
                'parameters' => [
                    'candidate_labels' => ['test', 'connexion']
                ]
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service IA opérationnel',
                    'model' => 'facebook/bart-large-mnli'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de connexion',
                    'details' => $response->body()
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }
}