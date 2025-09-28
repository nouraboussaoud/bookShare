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

        // Get category IDs
        $categories = \App\Models\Category::all();
        $techCat = $categories->where('name', 'Informatique')->first()->id ?? $categories->first()->id;
        $fictionCat = $categories->where('name', 'Fiction')->first()->id ?? $categories->first()->id;
        $scienceCat = $categories->where('name', 'Sciences')->first()->id ?? $categories->first()->id;
        $businessCat = $categories->where('name', 'Business')->first()->id ?? $categories->first()->id;

        $books = [
            // Programming & Technology
            ['title' => 'Laravel: From Apprentice to Artisan', 'author' => 'Taylor Otwell', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'A comprehensive guide to Laravel framework'],
            ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'author' => 'Robert C. Martin', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Best practices for writing clean, maintainable code'],
            ['title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'author' => 'Gang of Four', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Classic book on software design patterns'],
            ['title' => 'The Pragmatic Programmer: Your Journey To Mastery', 'author' => 'David Thomas', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Essential reading for software developers'],
            ['title' => 'Refactoring: Improving the Design of Existing Code', 'author' => 'Martin Fowler', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Guide to improving code structure'],
            ['title' => 'JavaScript: The Good Parts', 'author' => 'Douglas Crockford', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Essential JavaScript concepts and best practices'],
            ['title' => 'You Don\'t Know JS: Up & Going', 'author' => 'Kyle Simpson', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Deep dive into JavaScript fundamentals'],
            ['title' => 'PHP: The Right Way', 'author' => 'Josh Lockhart', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Modern PHP development practices'],
            ['title' => 'Modern PHP: New Features and Good Practices', 'author' => 'Josh Lockhart', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Latest PHP features and techniques'],
            ['title' => 'Eloquent JavaScript: A Modern Introduction to Programming', 'author' => 'Marijn Haverbeke', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $techCat, 'description' => 'Comprehensive JavaScript programming guide'],

            // Fiction & Literature
            ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Classic American literature'],
            ['title' => '1984', 'author' => 'George Orwell', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Dystopian social science fiction novel'],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Classic romance novel'],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'American classic set in the Jazz Age'],
            ['title' => 'Harry Potter and the Philosopher\'s Stone', 'author' => 'J.K. Rowling', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'First book in the magical Harry Potter series'],
            ['title' => 'The Lord of the Rings: The Fellowship of the Ring', 'author' => 'J.R.R. Tolkien', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Epic fantasy adventure'],
            ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Coming-of-age story'],
            ['title' => 'Animal Farm', 'author' => 'George Orwell', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Allegorical novella about farm animals'],
            ['title' => 'Brave New World', 'author' => 'Aldous Huxley', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Dystopian social science fiction'],
            ['title' => 'The Hitchhiker\'s Guide to the Galaxy', 'author' => 'Douglas Adams', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Comedic science fiction series'],

            // Science & Education
            ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $scienceCat, 'description' => 'Popular science book about cosmology'],
            ['title' => 'Sapiens: A Brief History of Humankind', 'author' => 'Yuval Noah Harari', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $scienceCat, 'description' => 'Narrative of human history'],
            ['title' => 'The Selfish Gene', 'author' => 'Richard Dawkins', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $scienceCat, 'description' => 'Gene-centered view of evolution'],
            ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $scienceCat, 'description' => 'Popular science book about astronomy'],
            ['title' => 'The Art of War', 'author' => 'Sun Tzu', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $businessCat, 'description' => 'Ancient Chinese military treatise'],

            // Business & Self-Help
            ['title' => 'Think and Grow Rich', 'author' => 'Napoleon Hill', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $businessCat, 'description' => 'Personal development and success philosophy'],
            ['title' => 'The 7 Habits of Highly Effective People', 'author' => 'Stephen Covey', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $businessCat, 'description' => 'Self-help book on effectiveness'],
            ['title' => 'Rich Dad Poor Dad', 'author' => 'Robert Kiyosaki', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $businessCat, 'description' => 'Personal finance and investing'],
            ['title' => 'How to Win Friends and Influence People', 'author' => 'Dale Carnegie', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $businessCat, 'description' => 'Self-help book on interpersonal skills'],
            ['title' => 'The Lean Startup', 'author' => 'Eric Ries', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $businessCat, 'description' => 'Methodology for startup development'],

            // French Literature
            ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Conte philosophique et poétique'],
            ['title' => 'Les Misérables', 'author' => 'Victor Hugo', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Roman historique français'],
            ['title' => 'L\'Étranger', 'author' => 'Albert Camus', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Roman existentialiste'],
            ['title' => 'Madame Bovary', 'author' => 'Gustave Flaubert', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Roman réaliste du XIXe siècle'],
            ['title' => 'Le Comte de Monte-Cristo', 'author' => 'Alexandre Dumas', 'user_id' => $userIds[array_rand($userIds)], 'category_id' => $fictionCat, 'description' => 'Roman d\'aventures'],
        ];

        foreach ($books as $book) {
            \App\Models\Book::create($book);
        }

        $this->command->info('Created ' . count($books) . ' books in the database.');
    }
}
