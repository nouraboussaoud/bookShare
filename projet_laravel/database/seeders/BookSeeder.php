<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some user IDs for book ownership
        $userIds = \App\Models\User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $books = [
            ['title' => 'Laravel: From Apprentice to Artisan', 'author' => 'Taylor Otwell', 'status' => 'available'],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'status' => 'available'],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas', 'status' => 'available'],
            ['title' => '1984', 'author' => 'George Orwell', 'status' => 'available'],
            ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'status' => 'available'],
            ['title' => 'Harry Potter', 'author' => 'J.K. Rowling', 'status' => 'reserved'],
            ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'status' => 'available'],
        ];

        foreach ($books as $book) {
            \App\Models\Book::create([
                'title' => $book['title'],
                'author' => $book['author'],
                'status' => $book['status'],
                'user_id' => $userIds[array_rand($userIds)],
                'recommended_age' => 0,
            ]);
        }

        $this->command->info('Created ' . count($books) . ' books in the database.');
    }
}
