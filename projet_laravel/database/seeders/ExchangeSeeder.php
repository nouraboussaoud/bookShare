<?php

namespace Database\Seeders;

use App\Models\Exchange;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExchangeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get all users and books
        $users = User::all();
        $books = Book::all();

        if ($users->count() < 2 || $books->count() < 1) {
            $this->command->warn('Not enough users or books to create exchanges. Please run UserSeeder and BookSeeder first.');
            return;
        }

        $exchanges = [
            [
                'type' => 'ECHANGE',
                'status' => 'EN_ATTENTE',
                'dateDebut' => Carbon::now()->addDays(1),
                'dateFin' => Carbon::now()->addDays(8),
                'userInitiateurId' => $users->random()->id,
                'userRecepteurId' => $users->random()->id,
                'bookDemandeId' => $books->random()->id,
                'bookOffertId' => $books->random()->id,
            ],
            [
                'type' => 'RESERVATION',
                'status' => 'EN_COURS',
                'dateDebut' => Carbon::now()->subDays(2),
                'dateFin' => Carbon::now()->addDays(5),
                'userInitiateurId' => $users->random()->id,
                'userRecepteurId' => $users->random()->id,
                'bookDemandeId' => $books->random()->id,
                'bookOffertId' => null,
            ],
            [
                'type' => 'ECHANGE',
                'status' => 'TERMINE',
                'dateDebut' => Carbon::now()->subDays(10),
                'dateFin' => Carbon::now()->subDays(3),
                'userInitiateurId' => $users->random()->id,
                'userRecepteurId' => $users->random()->id,
                'bookDemandeId' => $books->random()->id,
                'bookOffertId' => $books->random()->id,
            ],
            [
                'type' => 'RESERVATION',
                'status' => 'REFUSE',
                'dateDebut' => Carbon::now()->addDays(3),
                'dateFin' => Carbon::now()->addDays(10),
                'userInitiateurId' => $users->random()->id,
                'userRecepteurId' => $users->random()->id,
                'bookDemandeId' => $books->random()->id,
                'bookOffertId' => null,
            ],
            [
                'type' => 'ECHANGE',
                'status' => 'EN_COURS',
                'dateDebut' => Carbon::now()->subDays(1),
                'dateFin' => Carbon::now()->addDays(6),
                'userInitiateurId' => $users->random()->id,
                'userRecepteurId' => $users->random()->id,
                'bookDemandeId' => $books->random()->id,
                'bookOffertId' => $books->random()->id,
            ],
        ];

        foreach ($exchanges as $exchangeData) {
            // Make sure initiator and receiver are different
            while ($exchangeData['userInitiateurId'] === $exchangeData['userRecepteurId']) {
                $exchangeData['userRecepteurId'] = $users->random()->id;
            }

            Exchange::create($exchangeData);
        }

        // Create additional random exchanges
        Exchange::factory(8)->create();

        $this->command->info('Created ' . Exchange::count() . ' exchanges successfully!');
    }
}