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
            // Create a default user if none exist
            $defaultUser = \App\Models\User::create([
                'name' => 'Book Owner',
                'email' => 'bookowner@bookshare.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user',
            ]);
            $userIds = [$defaultUser->id];
        }

        $books = [
            // Programming & Technology
            ['title' => 'Laravel: From Apprentice to Artisan', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Pragmatic Programmer: Your Journey To Mastery', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Refactoring: Improving the Design of Existing Code', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'JavaScript: The Good Parts', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'You Don\'t Know JS: Up & Going', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'PHP: The Right Way', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Modern PHP: New Features and Good Practices', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Eloquent JavaScript: A Modern Introduction to Programming', 'user_id' => $userIds[array_rand($userIds)]],

            // Fiction & Literature
            ['title' => 'To Kill a Mockingbird', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => '1984', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Pride and Prejudice', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Great Gatsby', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Harry Potter and the Philosopher\'s Stone', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Lord of the Rings: The Fellowship of the Ring', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Catcher in the Rye', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Animal Farm', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Brave New World', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Hitchhiker\'s Guide to the Galaxy', 'user_id' => $userIds[array_rand($userIds)]],

            // Science & Education
            ['title' => 'A Brief History of Time', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Sapiens: A Brief History of Humankind', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Selfish Gene', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Cosmos', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Art of War', 'user_id' => $userIds[array_rand($userIds)]],

            // Business & Self-Help
            ['title' => 'Think and Grow Rich', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The 7 Habits of Highly Effective People', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Rich Dad Poor Dad', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'How to Win Friends and Influence People', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Lean Startup', 'user_id' => $userIds[array_rand($userIds)]],

            // French Literature
            ['title' => 'Le Petit Prince', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Les Misérables', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'L\'Étranger', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Madame Bovary', 'user_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Le Comte de Monte-Cristo', 'user_id' => $userIds[array_rand($userIds)]],
        ];

        foreach ($books as $book) {
            \App\Models\Book::create($book);
        }

        $this->command->info('Created ' . count($books) . ' books in the database.');
    }
}
