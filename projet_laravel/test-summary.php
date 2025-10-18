<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Book;
use App\Services\BookSummaryService;

echo "🧪 Test de Génération de Résumé IA\n";
echo "===================================\n\n";

// Trouver le livre "miserable"
$book = Book::where('title', 'LIKE', '%miserable%')->first();

if (!$book) {
    echo "❌ Livre 'miserable' non trouvé!\n";
    exit(1);
}

echo "📚 Livre trouvé:\n";
echo "   Titre: {$book->title}\n";
echo "   Auteur: {$book->author}\n";
echo "   Propriétaire ID: {$book->user_id}\n";
echo "   Catégorie: " . ($book->category ? $book->category->name : 'Aucune') . "\n";
echo "   Description: " . ($book->description ? substr($book->description, 0, 50) . '...' : 'Aucune') . "\n";
echo "   Résumé IA actuel: " . ($book->ai_summary ? 'Existe déjà' : 'Aucun') . "\n\n";

// Tester la génération
echo "🤖 Génération du résumé IA...\n";

$summaryService = new BookSummaryService();
$result = $summaryService->generateSummary($book);

if ($result['success']) {
    echo "✅ Résumé généré avec succès!\n\n";
    echo "📝 Résumé:\n";
    echo "---\n";
    echo $result['summary'] . "\n";
    echo "---\n\n";
    
    // Vérifier que c'est bien sauvegardé
    $book->refresh();
    echo "💾 Sauvegardé en base de données: " . ($book->ai_summary ? "✅ Oui" : "❌ Non") . "\n";
} else {
    echo "❌ Échec de la génération!\n";
    echo "Erreur: " . $result['message'] . "\n";
}

echo "\n";
