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
            ['title' => 'Laravel: From Apprentice to Artisan', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Pragmatic Programmer: Your Journey To Mastery', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Refactoring: Improving the Design of Existing Code', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'JavaScript: The Good Parts', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'You Don\'t Know JS: Up & Going', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'PHP: The Right Way', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Modern PHP: New Features and Good Practices', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Eloquent JavaScript: A Modern Introduction to Programming', 'owner_id' => $userIds[array_rand($userIds)]],

            // Fiction & Literature
            ['title' => 'To Kill a Mockingbird', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => '1984', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Pride and Prejudice', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Great Gatsby', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Harry Potter and the Philosopher\'s Stone', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Lord of the Rings: The Fellowship of the Ring', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Catcher in the Rye', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Animal Farm', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Brave New World', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Hitchhiker\'s Guide to the Galaxy', 'owner_id' => $userIds[array_rand($userIds)]],

            // Science & Education
            ['title' => 'A Brief History of Time', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Sapiens: A Brief History of Humankind', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Selfish Gene', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Cosmos', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Art of War', 'owner_id' => $userIds[array_rand($userIds)]],

            // Business & Self-Help
            ['title' => 'Think and Grow Rich', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The 7 Habits of Highly Effective People', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Rich Dad Poor Dad', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'How to Win Friends and Influence People', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'The Lean Startup', 'owner_id' => $userIds[array_rand($userIds)]],

            // French Literature
            ['title' => 'Le Petit Prince', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Les Misérables', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'L\'Étranger', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Madame Bovary', 'owner_id' => $userIds[array_rand($userIds)]],
            ['title' => 'Le Comte de Monte-Cristo', 'owner_id' => $userIds[array_rand($userIds)]],
        ];

        foreach ($books as $book) {
            \App\Models\Book::create($book);
        }

        $this->command->info('Created ' . count($books) . ' books in the database.');
    }
}
