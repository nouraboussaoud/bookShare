<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin par défaut (seulement s'il n'existe pas)
        User::firstOrCreate(
            ['email' => 'admin@bookshare.com'],
            [
                'name' => 'Admin BookShare',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer un utilisateur normal par défaut (seulement s'il n'existe pas)
        User::firstOrCreate(
            ['email' => 'user@bookshare.com'],
            [
                'name' => 'User Test',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
