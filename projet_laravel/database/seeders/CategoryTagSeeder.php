<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTag;

class CategoryTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        // Tags par catégorie
        $tagsByCategory = [
            'Science-Fiction' => [
                ['name' => 'Dystopie', 'type' => 'genre', 'color' => '#dc3545', 'icon' => 'fas fa-city', 'description' => 'Futur sombre et oppressif'],
                ['name' => 'Space Opera', 'type' => 'genre', 'color' => '#6f42c1', 'icon' => 'fas fa-rocket', 'description' => 'Aventures spatiales épiques'],
                ['name' => 'Cyberpunk', 'type' => 'genre', 'color' => '#fd7e14', 'icon' => 'fas fa-robot', 'description' => 'Technologie et société dystopique'],
                ['name' => 'Voyage Temporel', 'type' => 'theme', 'color' => '#20c997', 'icon' => 'fas fa-clock', 'description' => 'Manipulation du temps'],
                ['name' => 'Intelligence Artificielle', 'type' => 'theme', 'color' => '#17a2b8', 'icon' => 'fas fa-brain', 'description' => 'IA et conscience'],
                ['name' => 'Post-Apocalyptique', 'type' => 'theme', 'color' => '#6c757d', 'icon' => 'fas fa-radiation', 'description' => 'Après la catastrophe'],
            ],
            
            'Romance' => [
                ['name' => 'Romance Contemporaine', 'type' => 'genre', 'color' => '#e83e8c', 'icon' => 'fas fa-heart', 'description' => 'Romance moderne'],
                ['name' => 'Romance Historique', 'type' => 'genre', 'color' => '#6f42c1', 'icon' => 'fas fa-crown', 'description' => 'Romance d\'époque'],
                ['name' => 'Romance Paranormale', 'type' => 'genre', 'color' => '#6610f2', 'icon' => 'fas fa-moon', 'description' => 'Romance avec éléments surnaturels'],
                ['name' => 'Enemies to Lovers', 'type' => 'theme', 'color' => '#dc3545', 'icon' => 'fas fa-fire', 'description' => 'D\'ennemis à amants'],
                ['name' => 'Slow Burn', 'type' => 'pace', 'color' => '#fd7e14', 'icon' => 'fas fa-hourglass-half', 'description' => 'Développement lent de la romance'],
                ['name' => 'Second Chance', 'type' => 'theme', 'color' => '#20c997', 'icon' => 'fas fa-redo', 'description' => 'Seconde chance en amour'],
            ],
            
            'Policier' => [
                ['name' => 'Thriller Psychologique', 'type' => 'genre', 'color' => '#6610f2', 'icon' => 'fas fa-brain', 'description' => 'Suspense psychologique'],
                ['name' => 'Noir', 'type' => 'genre', 'color' => '#343a40', 'icon' => 'fas fa-user-secret', 'description' => 'Atmosphère sombre et cynique'],
                ['name' => 'Cozy Mystery', 'type' => 'genre', 'color' => '#ffc107', 'icon' => 'fas fa-coffee', 'description' => 'Enquête légère et chaleureuse'],
                ['name' => 'Détective Privé', 'type' => 'theme', 'color' => '#6c757d', 'icon' => 'fas fa-user-tie', 'description' => 'Enquêteur privé'],
                ['name' => 'Serial Killer', 'type' => 'theme', 'color' => '#dc3545', 'icon' => 'fas fa-skull', 'description' => 'Tueur en série'],
                ['name' => 'Complot', 'type' => 'theme', 'color' => '#fd7e14', 'icon' => 'fas fa-chess', 'description' => 'Conspiration et intrigue'],
            ],
            
            'Fantasy' => [
                ['name' => 'High Fantasy', 'type' => 'genre', 'color' => '#6f42c1', 'icon' => 'fas fa-dragon', 'description' => 'Monde imaginaire complet'],
                ['name' => 'Urban Fantasy', 'type' => 'genre', 'color' => '#17a2b8', 'icon' => 'fas fa-city', 'description' => 'Magie dans le monde moderne'],
                ['name' => 'Dark Fantasy', 'type' => 'genre', 'color' => '#343a40', 'icon' => 'fas fa-skull-crossbones', 'description' => 'Fantasy sombre et mature'],
                ['name' => 'Dragons', 'type' => 'theme', 'color' => '#dc3545', 'icon' => 'fas fa-dragon', 'description' => 'Présence de dragons'],
                ['name' => 'Magie', 'type' => 'theme', 'color' => '#6610f2', 'icon' => 'fas fa-hat-wizard', 'description' => 'Système de magie'],
                ['name' => 'Quête Épique', 'type' => 'theme', 'color' => '#ffc107', 'icon' => 'fas fa-map-marked-alt', 'description' => 'Grande quête héroïque'],
            ],
            
            'Horreur' => [
                ['name' => 'Horreur Psychologique', 'type' => 'genre', 'color' => '#6610f2', 'icon' => 'fas fa-brain', 'description' => 'Terreur mentale'],
                ['name' => 'Horreur Surnaturelle', 'type' => 'genre', 'color' => '#6f42c1', 'icon' => 'fas fa-ghost', 'description' => 'Entités surnaturelles'],
                ['name' => 'Gore', 'type' => 'genre', 'color' => '#dc3545', 'icon' => 'fas fa-tint', 'description' => 'Violence graphique'],
                ['name' => 'Vampires', 'type' => 'theme', 'color' => '#6c757d', 'icon' => 'fas fa-tooth', 'description' => 'Créatures vampiriques'],
                ['name' => 'Zombies', 'type' => 'theme', 'color' => '#28a745', 'icon' => 'fas fa-biohazard', 'description' => 'Morts-vivants'],
                ['name' => 'Maison Hantée', 'type' => 'theme', 'color' => '#343a40', 'icon' => 'fas fa-home', 'description' => 'Lieu hanté'],
            ],
        ];

        foreach ($categories as $category) {
            if (isset($tagsByCategory[$category->name])) {
                foreach ($tagsByCategory[$category->name] as $tagData) {
                    CategoryTag::create([
                        'category_id' => $category->id,
                        'name' => $tagData['name'],
                        'type' => $tagData['type'],
                        'color' => $tagData['color'],
                        'icon' => $tagData['icon'] ?? null,
                        'description' => $tagData['description'] ?? null,
                    ]);
                }
            }
        }

        $this->command->info('Tags créés avec succès pour chaque catégorie!');
    }
}
