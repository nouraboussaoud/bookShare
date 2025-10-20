<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Test de Configuration OpenAI\n";
echo "================================\n\n";

// Test 1: Vérifier que la clé existe dans .env
$envFile = file_get_contents(__DIR__.'/.env');
$hasKey = strpos($envFile, 'OPENAI_API_KEY') !== false;

echo "1. Clé dans .env: " . ($hasKey ? "✅ Trouvée" : "❌ Manquante") . "\n";

// Test 2: Vérifier que la config est chargée
$apiKey = config('openai.api_key');
echo "2. Config chargée: " . ($apiKey ? "✅ Oui" : "❌ Non") . "\n";

// Test 3: Vérifier le format de la clé
if ($apiKey) {
    $startsWithSk = str_starts_with($apiKey, 'sk-');
    echo "3. Format clé (sk-...): " . ($startsWithSk ? "✅ Correct" : "❌ Incorrect") . "\n";
    echo "4. Longueur clé: " . strlen($apiKey) . " caractères\n";
    echo "5. Aperçu: " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -4) . "\n";
} else {
    echo "3. Format clé: ❌ Pas de clé à vérifier\n";
}

echo "\n================================\n";

if ($apiKey && str_starts_with($apiKey, 'sk-')) {
    echo "✅ Configuration OpenAI OK!\n";
    echo "Vous pouvez maintenant tester le chatbot.\n";
} else {
    echo "❌ Configuration OpenAI incomplète!\n";
    echo "\nÉtapes à suivre:\n";
    echo "1. Obtenez une clé API sur https://platform.openai.com/api-keys\n";
    echo "2. Ajoutez-la dans votre fichier .env:\n";
    echo "   OPENAI_API_KEY=sk-votre-cle-ici\n";
    echo "3. Exécutez: php artisan config:clear\n";
    echo "4. Relancez ce test: php test-openai.php\n";
}

echo "\n";
