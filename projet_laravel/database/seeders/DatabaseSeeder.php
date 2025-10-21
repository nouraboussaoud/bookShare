<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin and test users first
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Create more regular users (only if they don't exist)
        if (User::count() <= 2) { // Only admin and test user from AdminUserSeeder
            User::factory(8)->create();
        }
        
        // Create categories
        $this->call([
            CategorySeeder::class,
        ]);
        
        // Create books (this will use the existing users)
        $this->call([
            BookSeeder::class,
        ]);
        
        // Create exchanges (depends on users and books)
        $this->call([
            ExchangeSeeder::class,
        ]);
        
        // Create locations/reservations (depends on users and books)
        $this->call([
            LocationSeeder::class,
        ]);
        
        // Create reports (depends on users and exchanges)
        $this->call([
            ReportSeeder::class,
        ]);
        
        // Create notifications (depends on users, exchanges, and reports)
        $this->call([
            NotificationSeeder::class,
        ]);
        
        // Create reading groups with events (depends on users)
        $this->call([
            ReadingGroupSeeder::class,
        ]);
    }
}
