<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['id' => 1, 'name' => 'Nour Admin', 'email' => 'nour.admin@bookshare.com'],
            ['id' => 2, 'name' => 'Manar Ahmed', 'email' => 'manar.ahmed@bookshare.com'],
            ['id' => 3, 'name' => 'Ahmed Mohamed', 'email' => 'ahmed.mohamed@bookshare.com'],
            ['id' => 4, 'name' => 'Mariem Ali', 'email' => 'mariem.ali@bookshare.com'],
            ['id' => 5, 'name' => 'Fatma Zahra', 'email' => 'fatma.zahra@bookshare.com'],
            ['id' => 6, 'name' => 'Youssef Hassan', 'email' => 'youssef.hassan@bookshare.com'],
            ['id' => 7, 'name' => 'Zeinab Karim', 'email' => 'zeinab.karim@bookshare.com'],
            ['id' => 8, 'name' => 'Omar Taher', 'email' => 'omar.taher@bookshare.com'],
            ['id' => 9, 'name' => 'Nour Aboussaoud', 'email' => 'nour.aboussaoud@esprit.tn']
        ];

        foreach ($users as $userData) {
            $user = User::find($userData['id']);
            if ($user) {
                $user->update([
                    'name' => $userData['name'],
                    'email' => $userData['email']
                ]);
                $this->command->info("Updated user ID {$userData['id']}: {$userData['name']}");
            }
        }

        $this->command->info('All users updated successfully!');
    }
}