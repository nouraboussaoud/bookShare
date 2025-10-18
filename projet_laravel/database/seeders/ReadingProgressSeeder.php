<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReadingProgress;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class ReadingProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques utilisateurs et livres
        $users = User::where('role', 'user')->take(3)->get();
        $books = Book::take(10)->get();

        if ($users->isEmpty() || $books->isEmpty()) {
            $this->command->warn('Pas assez d\'utilisateurs ou de livres pour créer des progressions de lecture.');
            return;
        }

        $this->command->info('Création de progressions de lecture...');

        foreach ($users as $user) {
            // Chaque utilisateur aura plusieurs progressions
            
            // 1. Un livre en cours de lecture
            if ($books->count() > 0) {
                ReadingProgress::create([
                    'user_id' => $user->id,
                    'book_id' => $books[0]->id,
                    'current_page' => 150,
                    'total_pages' => 350,
                    'status' => 'reading',
                    'started_at' => Carbon::now()->subDays(7),
                    'reading_time_minutes' => 420, // 7 heures
                    'notes' => 'Très captivant ! J\'adore l\'intrigue.',
                ]);
            }

            // 2. Un livre terminé
            if ($books->count() > 1) {
                ReadingProgress::create([
                    'user_id' => $user->id,
                    'book_id' => $books[1]->id,
                    'current_page' => 280,
                    'total_pages' => 280,
                    'status' => 'completed',
                    'started_at' => Carbon::now()->subDays(30),
                    'finished_at' => Carbon::now()->subDays(15),
                    'reading_time_minutes' => 600, // 10 heures
                    'notes' => 'Excellent livre ! Je le recommande.',
                ]);
            }

            // 3. Un livre à lire
            if ($books->count() > 2) {
                ReadingProgress::create([
                    'user_id' => $user->id,
                    'book_id' => $books[2]->id,
                    'current_page' => 0,
                    'total_pages' => 450,
                    'status' => 'to_read',
                    'notes' => 'Recommandé par un ami.',
                ]);
            }

            // 4. Un livre abandonné
            if ($books->count() > 3) {
                ReadingProgress::create([
                    'user_id' => $user->id,
                    'book_id' => $books[3]->id,
                    'current_page' => 50,
                    'total_pages' => 300,
                    'status' => 'abandoned',
                    'started_at' => Carbon::now()->subDays(60),
                    'reading_time_minutes' => 90,
                    'notes' => 'Pas vraiment mon style.',
                ]);
            }

            // 5. Un autre livre en cours
            if ($books->count() > 4) {
                ReadingProgress::create([
                    'user_id' => $user->id,
                    'book_id' => $books[4]->id,
                    'current_page' => 75,
                    'total_pages' => 200,
                    'status' => 'reading',
                    'started_at' => Carbon::now()->subDays(3),
                    'reading_time_minutes' => 120,
                ]);
            }
        }

        $this->command->info('✓ Progressions de lecture créées avec succès !');
    }
}
