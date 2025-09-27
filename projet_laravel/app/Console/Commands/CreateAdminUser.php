<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Vérifier si un admin existe déjà
        $existingAdmin = User::where('email', 'admin@bookshare.com')->first();
        
        if ($existingAdmin) {
            $this->info('Admin user already exists: admin@bookshare.com');
            return;
        }

        // Créer l'utilisateur admin
        $user = User::create([
            'name' => 'Admin BookShare',
            'email' => 'admin@bookshare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@bookshare.com');
        $this->info('Password: password');
    }
}