<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Report;

class AIEmotionController extends Controller
{
    /**
     * Analyser les émotions dans un texte de signalement avec l'IA Hugging Face
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyzeEmotion(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|min:5|max:2000',
                'report_id' => 'nullable|integer|exists:reports,id'
            ]);

            $text = $request->input('text');
            $reportId = $request->input('report_id');

            Log::info('🧠 [EMOTION AI] Début analyse émotion', [
                'text_length' => strlen($text),
                'report_id' => $reportId,
                'user_id' => auth()->id()
            ]);

            // Appel à l'API Hugging Face pour analyse d'émotions
            $emotionData = $this->callHuggingFaceEmotion($text);

            if (!$emotionData) {
                // Fallback: analyse basique par mots-clés si l'API échoue
                Log::warning('🔄 [EMOTION AI] API échouée, utilisation fallback', [
                    'text_length' => strlen($text),
                    'user_id' => auth()->id()
                ]);
                
                $emotionData = $this->fallbackEmotionAnalysis($text);
            }

            // Traitement et classification des émotions
            $processedEmotion = $this->processEmotionResults($emotionData);

            // Déterminer la priorité selon l'émotion
            $priority = $this->calculatePriority($processedEmotion);

            // Générer message empathique automatique
            $empathicMessage = $this->generateEmpathicResponse($processedEmotion);

            // Si c'est un signalement existant, mettre à jour en base
            if ($reportId) {
                $this->updateReportWithEmotion($reportId, $processedEmotion, $priority);
            }

            Log::info('✅ [EMOTION AI] Analyse terminée avec succès', [
                'emotion_type' => $processedEmotion['emotion'],
                'emotion_score' => $processedEmotion['confidence'],
                'priority_level' => $priority,
                'report_id' => $reportId
            ]);

            return response()->json([
                'success' => true,
                'emotion_analysis' => [
                    'emotion' => $processedEmotion['emotion'],
                    'confidence' => $processedEmotion['confidence'],
                    'intensity' => $processedEmotion['intensity'],
                    'priority_level' => $priority,
                    'empathic_message' => $empathicMessage,
                    'all_emotions' => $processedEmotion['all_scores'] ?? []
                ],
                'recommendations' => $this->getHandlingRecommendations($processedEmotion, $priority)
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [EMOTION AI] Erreur analyse émotion: ' . $e->getMessage(), [
                'text' => substr($text ?? 'N/A', 0, 100),
                'report_id' => $reportId ?? null,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'analyse des émotions',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
            ], 500);
        }
    }

    /**
     * Appel à l'API Hugging Face pour l'analyse d'émotions
     */
    private function callHuggingFaceEmotion($text)
    {
        try {
            $apiKey = env('HUGGINGFACE_API_KEY');
            
            if (!$apiKey) {
                Log::error('❌ [EMOTION AI] Clé API Hugging Face manquante');
                return null;
            }

            // Modèle spécialisé dans la détection d'émotions
            $response = Http::timeout(12)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api-inference.huggingface.co/models/j-hartmann/emotion-english-distilroberta-base', [
                    'inputs' => $text,
                    'options' => [
                        'wait_for_model' => true,
                        'use_cache' => false
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('✅ [EMOTION AI] Réponse Hugging Face reçue', [
                    'response_size' => count($data),
                    'full_response' => $data, // Ajoutons la réponse complète pour debug
                    'first_emotion' => isset($data[0]['label']) ? $data[0]['label'] : 'N/A'
                ]);
                return $data;
            }

            Log::error('❌ [EMOTION AI] Erreur API Hugging Face', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('❌ [EMOTION AI] Exception API call: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Traiter et normaliser les résultats d'émotions
     */
    private function processEmotionResults($emotionData)
    {
        // Validation de la structure de données
        if (!is_array($emotionData) || empty($emotionData)) {
            throw new \Exception('Réponse API invalide : données d\'émotions vides');
        }

        // Différentes structures possibles de l'API Hugging Face
        $emotions = [];
        
        // Structure 1: Array direct avec label/score
        if (isset($emotionData[0]['label']) && isset($emotionData[0]['score'])) {
            $emotions = $emotionData;
        }
        // Structure 2: Objet avec propriétés émotions
        elseif (is_array($emotionData) && count($emotionData) > 0) {
            // Essayer de détecter d'autres structures
            $firstItem = $emotionData[0];
            if (is_array($firstItem)) {
                // Si c'est un array de arrays
                foreach ($emotionData as $item) {
                    if (is_array($item) && count($item) >= 2) {
                        $emotions[] = [
                            'label' => $item[0] ?? 'unknown',
                            'score' => is_numeric($item[1]) ? $item[1] : 0.5
                        ];
                    }
                }
            }
        }

        // Si on n'arrive toujours pas à parser, créer une émotion par défaut
        if (empty($emotions)) {
            Log::warning('Structure API inconnue, utilisation émotion par défaut', [
                'raw_data' => $emotionData
            ]);
            
            $emotions = [
                ['label' => 'neutral', 'score' => 0.7]
            ];
        }

        // Normalisation des labels d'émotions (anglais → français)
        $emotionMapping = [
            'anger' => 'colère',
            'disgust' => 'dégoût',
            'fear' => 'peur', 
            'joy' => 'joie',
            'sadness' => 'tristesse',
            'surprise' => 'surprise',
            'neutral' => 'neutre',
            'unknown' => 'neutre'
        ];

        // Trouver l'émotion dominante
        $topEmotion = $emotions[0];
        $emotionKey = strtolower($topEmotion['label']);
        $emotion = $emotionMapping[$emotionKey] ?? $emotionKey;
        $confidence = round($topEmotion['score'] * 100, 1);

        // Déterminer l'intensité
        $intensity = $this->calculateIntensity($confidence);

        // Toutes les émotions avec scores
        $allScores = [];
        foreach ($emotions as $emotionItem) {
            if (isset($emotionItem['label']) && isset($emotionItem['score'])) {
                $key = strtolower($emotionItem['label']);
                $allScores[$emotionMapping[$key] ?? $key] = round($emotionItem['score'] * 100, 1);
            }
        }

        return [
            'emotion' => $emotion,
            'confidence' => $confidence,
            'intensity' => $intensity,
            'all_scores' => $allScores
        ];
    }

    /**
     * Calculer l'intensité de l'émotion
     */
    private function calculateIntensity($confidence)
    {
        if ($confidence >= 80) return 'très_forte';
        if ($confidence >= 60) return 'forte';
        if ($confidence >= 40) return 'modérée';
        return 'faible';
    }

    /**
     * Calculer la priorité selon l'émotion détectée
     */
    private function calculatePriority($emotionData)
    {
        $emotion = $emotionData['emotion'];
        $confidence = $emotionData['confidence'];
        $intensity = $emotionData['intensity'];

        // Émotions urgentes qui nécessitent une attention immédiate
        $urgentEmotions = ['colère', 'peur', 'dégoût'];
        
        // Émotions modérées
        $moderateEmotions = ['tristesse'];
        
        // Émotions basses priorités
        $lowEmotions = ['surprise', 'joie', 'neutre'];

        if (in_array($emotion, $urgentEmotions)) {
            if ($intensity === 'très_forte' || $confidence >= 75) {
                return 'critique'; // Traitement immédiat
            } elseif ($intensity === 'forte' || $confidence >= 55) {
                return 'haute'; // Traitement dans les 2h
            } else {
                return 'moyenne'; // Traitement dans la journée
            }
        }

        if (in_array($emotion, $moderateEmotions)) {
            if ($intensity === 'très_forte' || $confidence >= 70) {
                return 'haute';
            } else {
                return 'moyenne';
            }
        }

        return 'normale'; // Traitement standard
    }

    /**
     * Générer une réponse empathique automatique
     */
    private function generateEmpathicResponse($emotionData)
    {
        $emotion = $emotionData['emotion'];
        $intensity = $emotionData['intensity'];

        $responses = [
            'colère' => [
                'très_forte' => "Je comprends que vous soyez très en colère. Votre signalement sera traité en priorité absolue.",
                'forte' => "Je vois que cette situation vous met en colère. Nous allons examiner cela rapidement.",
                'modérée' => "Je perçois votre frustration et nous allons nous en occuper.",
                'faible' => "Merci de nous avoir fait part de votre mécontentement."
            ],
            'peur' => [
                'très_forte' => "Je comprends que vous vous sentiez en danger. Nous prenons cela très au sérieux.",
                'forte' => "Votre inquiétude est légitime, nous allons traiter cela en urgence.",
                'modérée' => "Je comprends vos préoccupations et nous allons les examiner.",
                'faible' => "Merci de nous avoir fait part de vos inquiétudes."
            ],
            'tristesse' => [
                'très_forte' => "Je suis désolé que cette situation vous affecte autant. Nous allons faire notre possible.",
                'forte' => "Je comprends que cela soit difficile pour vous.",
                'modérée' => "Merci de nous avoir fait confiance pour résoudre cette situation.",
                'faible' => "Nous allons examiner votre signalement avec attention."
            ],
            'dégoût' => [
                'très_forte' => "Je comprends que cette situation soit inacceptable pour vous.",
                'forte' => "Votre réaction est compréhensible, nous allons traiter cela rapidement.",
                'modérée' => "Merci de nous avoir signalé cette situation problématique.",
                'faible' => "Nous prenons note de votre signalement."
            ]
        ];

        return $responses[$emotion][$intensity] ?? "Merci pour votre signalement, nous l'examinerons avec attention.";
    }

    /**
     * Mettre à jour le signalement avec les données d'émotion
     */
    private function updateReportWithEmotion($reportId, $emotionData, $priority)
    {
        try {
            $report = Report::find($reportId);
            if ($report) {
                $report->update([
                    'emotion_type' => $emotionData['emotion'],
                    'emotion_score' => $emotionData['confidence'],
                    'priority_level' => $priority
                ]);
                
                Log::info('✅ [EMOTION AI] Signalement mis à jour', [
                    'report_id' => $reportId,
                    'emotion' => $emotionData['emotion'],
                    'priority' => $priority
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ [EMOTION AI] Erreur mise à jour signalement: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir des recommandations de traitement selon l'émotion
     */
    private function getHandlingRecommendations($emotionData, $priority)
    {
        $recommendations = [];
        $emotion = $emotionData['emotion'];

        switch ($priority) {
            case 'critique':
                $recommendations[] = "⚠️ TRAITEMENT IMMÉDIAT requis";
                $recommendations[] = "Notifier l'administrateur principal";
                $recommendations[] = "Réponse empathique obligatoire sous 1h";
                break;

            case 'haute':
                $recommendations[] = "🔥 Traitement prioritaire dans les 2h";
                $recommendations[] = "Réponse personnalisée recommandée";
                break;

            case 'moyenne':
                $recommendations[] = "📋 Traitement dans la journée";
                $recommendations[] = "Suivi standard avec message empathique";
                break;

            default:
                $recommendations[] = "📄 Traitement selon procédure normale";
                break;
        }

        // Recommandations spécifiques selon l'émotion
        if ($emotion === 'colère') {
            $recommendations[] = "💬 Éviter les réponses defensives";
            $recommendations[] = "🤝 Proposer une solution concrète rapidement";
        } elseif ($emotion === 'peur') {
            $recommendations[] = "🛡️ Rassurer sur les mesures de sécurité";
            $recommendations[] = "📞 Proposer un contact direct si nécessaire";
        } elseif ($emotion === 'tristesse') {
            $recommendations[] = "💝 Réponse empathique et compréhensive";
            $recommendations[] = "🕐 Prendre le temps d'expliquer les étapes";
        }

        return $recommendations;
    }

    /**
     * Analyse d'émotion de fallback basée sur des mots-clés (si API échoue)
     */
    private function fallbackEmotionAnalysis($text)
    {
        $text = strtolower($text);
        
        // Dictionnaire de mots-clés par émotion
        $keywords = [
            'colère' => ['colère', 'en colère', 'furieux', 'énervé', 'irrité', 'insupportable', 'inacceptable', 'scandaleux', 'révoltant'],
            'peur' => ['peur', 'angoisse', 'inquiet', 'terrifié', 'effrayé', 'anxieux', 'stressé', 'panic'],
            'tristesse' => ['triste', 'déçu', 'malheureux', 'déprimé', 'chagrin', 'peine', 'désespoir'],
            'joie' => ['content', 'heureux', 'satisfait', 'ravi', 'enchanté', 'merci', 'parfait', 'excellent'],
            'dégoût' => ['dégoûtant', 'répugnant', 'horrible', 'écœurant', 'immonde'],
            'surprise' => ['surprenant', 'étonnant', 'incroyable', 'inattendu']
        ];

        $scores = [];
        
        // Calculer le score pour chaque émotion
        foreach ($keywords as $emotion => $words) {
            $score = 0;
            foreach ($words as $word) {
                if (strpos($text, $word) !== false) {
                    $score += 0.3; // Chaque mot-clé trouvé ajoute 30%
                }
            }
            $scores[$emotion] = min($score, 0.9); // Maximum 90%
        }
        
        // Si aucun mot-clé trouvé, émotion neutre
        if (array_sum($scores) == 0) {
            $scores['neutre'] = 0.7;
        }
        
        // Trier par score décroissant
        arsort($scores);
        
        // Convertir au format attendu
        $result = [];
        foreach ($scores as $emotion => $score) {
            if ($score > 0) {
                $result[] = [
                    'label' => $emotion,
                    'score' => $score
                ];
            }
        }
        
        return $result;
    }
}