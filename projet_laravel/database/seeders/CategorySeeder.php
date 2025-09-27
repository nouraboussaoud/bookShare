<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Anime/Manga',
                'description' => 'Livres inspirés d\'anime, manga et culture japonaise',
                'age_allowed' => 13,
                'color' => '#FF6B6B',
                'icon' => 'fas fa-star',
                'is_featured' => true,
                'sort_order' => 1,
                'reading_tips' => 'Découvrez des histoires captivantes avec des personnages attachants et des univers fantastiques.',
                'popular_authors' => ['Akira Toriyama', 'Masashi Kishimoto', 'Eiichiro Oda'],
                'is_active' => true,
            ],
            [
                'name' => 'Romance',
                'description' => 'Romans d\'amour, relations et histoires sentimentales',
                'age_allowed' => 16,
                'color' => '#EC4899',
                'icon' => 'fas fa-heart',
                'is_featured' => true,
                'sort_order' => 2,
                'reading_tips' => 'Plongez dans des histoires d\'amour émouvantes qui vous feront vibrer.',
                'popular_authors' => ['Nicholas Sparks', 'Jane Austen', 'Colleen Hoover'],
                'is_active' => true,
            ],
            [
                'name' => 'Science-Fiction',
                'description' => 'Aventures futuristes, technologie et mondes imaginaires',
                'age_allowed' => 12,
                'color' => '#8B5CF6',
                'icon' => 'fas fa-rocket',
                'is_featured' => true,
                'sort_order' => 3,
                'reading_tips' => 'Explorez des univers futuristes et des technologies révolutionnaires.',
                'popular_authors' => ['Isaac Asimov', 'Philip K. Dick', 'Arthur C. Clarke'],
                'is_active' => true,
            ],
            [
                'name' => 'Fantasy',
                'description' => 'Magie, créatures fantastiques et mondes enchantés',
                'age_allowed' => 10,
                'color' => '#10B981',
                'icon' => 'fas fa-dragon',
                'is_featured' => true,
                'sort_order' => 4,
                'reading_tips' => 'Laissez-vous emporter dans des mondes magiques remplis d\'aventures.',
                'popular_authors' => ['J.R.R. Tolkien', 'George R.R. Martin', 'Brandon Sanderson'],
                'is_active' => true,
            ],
            [
                'name' => 'Mystère/Thriller',
                'description' => 'Romans policiers, enquêtes et suspense',
                'age_allowed' => 15,
                'color' => '#374151',
                'icon' => 'fas fa-search',
                'is_featured' => false,
                'sort_order' => 5,
                'reading_tips' => 'Résolvez des énigmes captivantes et vivez des moments de suspense intense.',
                'popular_authors' => ['Agatha Christie', 'Arthur Conan Doyle', 'Gillian Flynn'],
                'is_active' => true,
            ],
            [
                'name' => 'Horreur',
                'description' => 'Histoires effrayantes et atmosphères terrifiantes',
                'age_allowed' => 18,
                'color' => '#DC2626',
                'icon' => 'fas fa-ghost',
                'is_featured' => false,
                'sort_order' => 6,
                'reading_tips' => 'Préparez-vous à des frissons et des moments de terreur pure.',
                'popular_authors' => ['Stephen King', 'H.P. Lovecraft', 'Clive Barker'],
                'is_active' => true,
            ],
            [
                'name' => 'Jeunesse',
                'description' => 'Livres pour adolescents et jeunes adultes',
                'age_allowed' => 0,
                'color' => '#F59E0B',
                'icon' => 'fas fa-child',
                'is_featured' => false,
                'sort_order' => 7,
                'reading_tips' => 'Des histoires qui parlent aux jeunes et les accompagnent dans leur croissance.',
                'popular_authors' => ['J.K. Rowling', 'Suzanne Collins', 'John Green'],
                'is_active' => true,
            ],
            [
                'name' => 'Biographie',
                'description' => 'Vies remarquables et témoignages personnels',
                'age_allowed' => 0,
                'color' => '#059669',
                'icon' => 'fas fa-user',
                'is_featured' => false,
                'sort_order' => 8,
                'reading_tips' => 'Découvrez des parcours inspirants et des leçons de vie.',
                'popular_authors' => ['Walter Isaacson', 'Michelle Obama', 'Malcolm X'],
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
