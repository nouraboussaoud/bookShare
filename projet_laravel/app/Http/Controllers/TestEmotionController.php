<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestEmotionController extends Controller
{
    public function testHuggingFace(Request $request)
    {
        try {
            $text = $request->input('text', 'I am very angry about this situation');
            
            $apiKey = env('HUGGINGFACE_API_KEY');
            
            if (!$apiKey) {
                return response()->json([
                    'error' => 'Clé API Hugging Face manquante'
                ]);
            }

            // Test avec le modèle d'émotions
            $response = Http::timeout(15)
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

            $data = $response->json();
            
            return response()->json([
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'raw_response' => $data,
                'response_type' => gettype($data),
                'is_array' => is_array($data),
                'count' => is_array($data) ? count($data) : 0,
                'first_element' => is_array($data) && !empty($data) ? $data[0] : null,
                'keys' => is_array($data) && !empty($data) && is_array($data[0]) ? array_keys($data[0]) : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}