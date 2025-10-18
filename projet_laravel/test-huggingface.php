<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🤗 Test de Configuration Hugging Face\n";
echo "======================================\n\n";

// Test 1: Vérifier que le token existe dans .env
$envFile = file_get_contents(__DIR__.'/.env');
$hasToken = strpos($envFile, 'HUGGINGFACE_TOKEN') !== false;

echo "1. Token dans .env: " . ($hasToken ? "✅ Trouvé" : "❌ Manquant") . "\n";

// Test 2: Vérifier que la config est chargée
$token = config('services.huggingface.token');
echo "2. Config chargée: " . ($token ? "✅ Oui" : "❌ Non") . "\n";

// Test 3: Vérifier le format du token
if ($token) {
    $startsWithHf = str_starts_with($token, 'hf_');
    echo "3. Format token (hf_...): " . ($startsWithHf ? "✅ Correct" : "❌ Incorrect") . "\n";
    echo "4. Longueur token: " . strlen($token) . " caractères\n";
    echo "5. Aperçu: " . substr($token, 0, 10) . "..." . substr($token, -4) . "\n";
    
    // Test 4: Tester l'API
    echo "\n6. Test de l'API Hugging Face...\n";
    
    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->timeout(10)->post('https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2', [
            'inputs' => '[INST] Dis bonjour en français [/INST]',
            'parameters' => [
                'max_new_tokens' => 50,
                'temperature' => 0.7,
            ],
        ]);
        
        if ($response->successful()) {
            echo "   ✅ API fonctionne!\n";
            $data = $response->json();
            if (isset($data[0]['generated_text'])) {
                echo "   Réponse test: " . substr($data[0]['generated_text'], 0, 100) . "\n";
            }
        } else {
            echo "   ❌ Erreur API: " . $response->status() . "\n";
            echo "   Message: " . $response->body() . "\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "3. Format token: ❌ Pas de token à vérifier\n";
}

echo "\n======================================\n";

if ($token && str_starts_with($token, 'hf_')) {
    echo "✅ Configuration Hugging Face OK!\n";
    echo "Vous pouvez maintenant tester le chatbot.\n";
} else {
    echo "❌ Configuration Hugging Face incomplète!\n";
    echo "\nÉtapes à suivre:\n";
    echo "1. Allez sur https://huggingface.co/settings/tokens\n";
    echo "2. Créez un nouveau token (type: Read)\n";
    echo "3. Ajoutez-le dans votre fichier .env:\n";
    echo "   HUGGINGFACE_TOKEN=hf_votre-token-ici\n";
    echo "4. Exécutez: php artisan config:clear\n";
    echo "5. Relancez ce test: php test-huggingface.php\n";
}

echo "\n";
