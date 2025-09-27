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
         // Create more regular users
        User::factory(5)->create();
        // Create admin and test users first
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Create additional test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
         // Create books (this will use the existing users)
        $this->call([
            BookSeeder::class,
        ]);
        
        // Appeler le seeder pour créer les utilisateurs admin et user
        $this->call(AdminUserSeeder::class);
        
        // Appeler le seeder pour créer les catégories
        $this->call(CategorySeeder::class);
    }
}
