<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Location;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs de test
        $users = [
            [
                'name' => 'Alice Martin',
                'email' => 'alice@bookshare.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active'
            ],
            [
                'name' => 'Bob Dupont',
                'email' => 'bob@bookshare.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active'
            ],
            [
                'name' => 'Claire Rousseau',
                'email' => 'claire@bookshare.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
            }
        }

        // Créer une catégorie par défaut si elle n'existe pas
        $category = Category::firstOrCreate([
            'name' => 'Roman'
        ], [
            'description' => 'Romans et littérature',
            'color' => '#007bff',
            'icon' => 'fas fa-book',
            'is_active' => true
        ]);

        // Créer des livres de test
        $books = [
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'description' => 'Un conte poétique et philosophique sous l\'apparence d\'un conte pour enfants.',
                'status' => 'available',
                'recommended_age' => 8,
                'user_id' => User::where('email', 'alice@bookshare.com')->first()->id,
                'category_id' => $category->id
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'Un roman dystopique qui dépeint une société totalitaire.',
                'status' => 'available',
                'recommended_age' => 16,
                'user_id' => User::where('email', 'bob@bookshare.com')->first()->id,
                'category_id' => $category->id
            ],
            [
                'title' => 'L\'Étranger',
                'author' => 'Albert Camus',
                'description' => 'Un roman qui explore l\'absurdité de la condition humaine.',
                'status' => 'available',
                'recommended_age' => 16,
                'user_id' => User::where('email', 'claire@bookshare.com')->first()->id,
                'category_id' => $category->id
            ],
            [
                'title' => 'Harry Potter à l\'école des sorciers',
                'author' => 'J.K. Rowling',
                'description' => 'Le premier tome de la saga Harry Potter.',
                'status' => 'available',
                'recommended_age' => 10,
                'user_id' => User::where('email', 'alice@bookshare.com')->first()->id,
                'category_id' => $category->id
            ],
            [
                'title' => 'Le Seigneur des Anneaux',
                'author' => 'J.R.R. Tolkien',
                'description' => 'Une épopée fantasy dans la Terre du Milieu.',
                'status' => 'available',
                'recommended_age' => 12,
                'user_id' => User::where('email', 'bob@bookshare.com')->first()->id,
                'category_id' => $category->id
            ]
        ];

        foreach ($books as $bookData) {
            if (!Book::where('title', $bookData['title'])->where('author', $bookData['author'])->exists()) {
                Book::create($bookData);
            }
        }

        // Créer quelques locations d'exemple
        $alice = User::where('email', 'alice@bookshare.com')->first();
        $bob = User::where('email', 'bob@bookshare.com')->first();
        $claire = User::where('email', 'claire@bookshare.com')->first();

        $book1984 = Book::where('title', '1984')->first();
        $bookEtranger = Book::where('title', 'L\'Étranger')->first();

        if ($alice && $bob && $claire && $book1984 && $bookEtranger) {
            // Location en attente : Claire veut louer 1984 de Bob
            if (!Location::where('book_id', $book1984->id)->where('locataire_id', $claire->id)->exists()) {
                $location1 = new Location();
                $location1->book_id = $book1984->id;
                $location1->proprietaire_id = $bob->id;
                $location1->locataire_id = $claire->id;
                $location1->date_location = Carbon::now()->addDays(2);
                $location1->duree_jours = 14;
                $location1->localisation = 'Bibliothèque Centrale';
                $location1->prix = 5.00;
                $location1->statut = 'en_attente';
                $location1->notes = 'J\'aimerais beaucoup lire ce classique !';
                $location1->calculerDateFin();
                $location1->save();
            }

            // Location en cours : Alice loue L'Étranger de Claire
            if (!Location::where('book_id', $bookEtranger->id)->where('locataire_id', $alice->id)->exists()) {
                $location2 = new Location();
                $location2->book_id = $bookEtranger->id;
                $location2->proprietaire_id = $claire->id;
                $location2->locataire_id = $alice->id;
                $location2->date_location = Carbon::now()->subDays(3);
                $location2->duree_jours = 10;
                $location2->localisation = 'Café du Coin';
                $location2->prix = 4.00;
                $location2->statut = 'en_cours';
                $location2->notes = 'Merci pour ce prêt !';
                $location2->calculerDateFin();
                $location2->save();
            }
        }

        $this->command->info('Données de test créées avec succès !');
        $this->command->info('Utilisateurs: ' . User::count());
        $this->command->info('Livres: ' . Book::count());
        $this->command->info('Locations: ' . Location::count());
    }
}
