<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Test de Configuration Groq API\n";
echo "==================================\n\n";

// Test 1: Vérifier que la clé existe dans .env
$envFile = file_get_contents(__DIR__.'/.env');
$hasKey = strpos($envFile, 'GROQ_API_KEY') !== false;

echo "1. Clé dans .env: " . ($hasKey ? "✅ Trouvée" : "❌ Manquante") . "\n";

// Test 2: Vérifier que la config est chargée
$apiKey = config('services.groq.api_key');
echo "2. Config chargée: " . ($apiKey ? "✅ Oui" : "❌ Non") . "\n";

// Test 3: Vérifier le format de la clé
if ($apiKey) {
    $startsWithGsk = str_starts_with($apiKey, 'gsk_');
    echo "3. Format clé (gsk_...): " . ($startsWithGsk ? "✅ Correct" : "❌ Incorrect") . "\n";
    echo "4. Longueur clé: " . strlen($apiKey) . " caractères\n";
    echo "5. Aperçu: " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -4) . "\n";
    
    // Test 4: Tester l'API
    echo "\n6. Test de l'API Groq...\n";
    
    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un assistant qui répond en français.',
                ],
                [
                    'role' => 'user',
                    'content' => 'Dis bonjour en une phrase courte.',
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 50,
        ]);
        
        if ($response->successful()) {
            echo "   ✅ API fonctionne parfaitement!\n";
            $data = $response->json();
            if (isset($data['choices'][0]['message']['content'])) {
                echo "   Réponse test: " . $data['choices'][0]['message']['content'] . "\n";
            }
        } else {
            echo "   ❌ Erreur API: " . $response->status() . "\n";
            echo "   Message: " . $response->body() . "\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "3. Format clé: ❌ Pas de clé à vérifier\n";
}

echo "\n==================================\n";

if ($apiKey && str_starts_with($apiKey, 'gsk_')) {
    echo "✅ Configuration Groq OK!\n";
    echo "Vous pouvez maintenant tester le chatbot.\n";
} else {
    echo "❌ Configuration Groq incomplète!\n";
    echo "\nÉtapes à suivre:\n";
    echo "1. Allez sur https://console.groq.com/keys\n";
    echo "2. Créez une nouvelle clé API\n";
    echo "3. Ajoutez-la dans votre fichier .env:\n";
    echo "   GROQ_API_KEY=gsk_votre-cle-ici\n";
    echo "4. Exécutez: php artisan config:clear\n";
    echo "5. Relancez ce test: php test-groq.php\n";
}

echo "\n";
