<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\Exchange;
use App\Models\Report;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = User::all();
        $exchanges = Exchange::all();
        $reports = Report::all();

        if ($users->count() < 2) {
            $this->command->warn('Not enough users to create notifications. Please run UserSeeder first.');
            return;
        }

        // Sample notifications
        $notifications = [
            [
                'user_id' => $users->where('role', 'user')->first()->id ?? $users->first()->id,
                'type' => 'exchange_request',
                'title' => 'Nouvelle demande d\'échange',
                'message' => 'Marie Dupont souhaite échanger votre livre "Le Petit Prince". Consultez les détails et acceptez ou refusez cette demande.',
                'data' => json_encode([
                    'exchange_id' => $exchanges->first()->id ?? 1,
                    'book_title' => 'Le Petit Prince',
                    'initiator_name' => 'Marie Dupont'
                ]),
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $users->where('role', 'user')->skip(1)->first()->id ?? $users->skip(1)->first()->id,
                'type' => 'exchange_status_change',
                'title' => 'Échange accepté',
                'message' => 'Votre demande d\'échange pour "1984" a été acceptée par Jean Martin.',
                'data' => json_encode([
                    'exchange_id' => $exchanges->skip(1)->first()->id ?? 2,
                    'book_title' => '1984',
                    'book_owner_name' => 'Jean Martin',
                    'new_status' => 'EN_COURS'
                ]),
                'is_read' => true,
                'read_at' => Carbon::now()->subHour(),
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'user_id' => $users->where('role', 'admin')->first()->id ?? $users->first()->id,
                'type' => 'new_report',
                'title' => 'Nouveau signalement',
                'message' => 'Un nouveau signalement de comportement inapproprié a été créé par Pierre Durand. Veuillez examiner ce signalement.',
                'data' => json_encode([
                    'report_id' => $reports->first()->id ?? 1,
                    'reporter_name' => 'Pierre Durand',
                    'report_type' => 'COMPORTEMENT'
                ]),
                'is_read' => false,
                'created_at' => Carbon::now()->subMinutes(30),
            ],
        ];

        foreach ($notifications as $notificationData) {
            Notification::create($notificationData);
        }

        $this->command->info('Created ' . Notification::count() . ' notifications successfully!');
    }
}