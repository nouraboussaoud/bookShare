<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;
use App\Models\CategoryTag;

echo "🏷️ Système de Tags par Catégorie\n";
echo "==================================\n\n";

$totalTags = CategoryTag::count();
echo "📊 Total de tags créés: {$totalTags}\n\n";

$categories = Category::with('categoryTags')->get();

foreach ($categories as $category) {
    $tagCount = $category->categoryTags->count();
    
    if ($tagCount > 0) {
        echo "📚 {$category->name} ({$tagCount} tags):\n";
        
        foreach ($category->categoryTags as $tag) {
            $icon = $tag->icon ? $tag->icon . ' ' : '';
            $typeLabel = match($tag->type) {
                'genre' => '📖 Genre',
                'theme' => '🎭 Thème',
                'mood' => '😊 Ambiance',
                'pace' => '⚡ Rythme',
                default => '🏷️ Autre'
            };
            
            echo "   {$icon}{$tag->name}\n";
            echo "      └─ {$typeLabel} | Couleur: {$tag->color}\n";
            if ($tag->description) {
                echo "      └─ {$tag->description}\n";
            }
        }
        echo "\n";
    }
}

echo "==================================\n";
echo "✅ Système de tags prêt à l'emploi!\n\n";

echo "💡 Prochaines étapes:\n";
echo "   1. Ajouter interface pour assigner tags aux livres\n";
echo "   2. Afficher tags sur les pages de livres\n";
echo "   3. Filtrer livres par tags\n";
echo "   4. Créer nuage de tags\n";

echo "\n";
