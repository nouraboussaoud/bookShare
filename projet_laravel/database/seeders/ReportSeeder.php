<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Exchange;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get all users and exchanges
        $users = User::all();
        $exchanges = Exchange::all();

        if ($users->count() < 3) {
            $this->command->warn('Not enough users to create reports. Please run UserSeeder first.');
            return;
        }

        // Create specific sample reports
        $sampleReports = [
            [
                'type' => Report::TYPE_COMPORTEMENT,
                'description' => 'Cet utilisateur a été très impoli lors de nos échanges de messages. Il a utilisé un langage inapproprié et a été irrespectueux. Je ne recommande pas de faire des échanges avec cette personne.',
                'status' => Report::STATUS_EN_ATTENTE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => $users->random()->id,
                'exchange_id' => null,
            ],
            [
                'type' => Report::TYPE_CONFLIT_ECHANGE,
                'description' => 'L\'utilisateur a accepté l\'échange mais ne s\'est jamais présenté au point de rendez-vous convenu. J\'ai attendu 30 minutes sans nouvelles. Cela m\'a fait perdre mon temps.',
                'status' => Report::STATUS_TRAITE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => null,
                'exchange_id' => $exchanges->isNotEmpty() ? $exchanges->random()->id : null,
            ],
            [
                'type' => Report::TYPE_COMPORTEMENT,
                'description' => 'Cette personne envoie des messages répétés et insistants même après que j\'ai décliné son offre d\'échange. C\'est du harcèlement.',
                'status' => Report::STATUS_TRAITE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => $users->random()->id,
                'exchange_id' => null,
            ],
            [
                'type' => Report::TYPE_CONFLIT_ECHANGE,
                'description' => 'Le livre reçu n\'était pas dans l\'état décrit. Il manquait plusieurs pages et la couverture était déchirée, contrairement à ce qui était annoncé.',
                'status' => Report::STATUS_EN_ATTENTE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => null,
                'exchange_id' => $exchanges->isNotEmpty() ? $exchanges->random()->id : null,
            ],
            [
                'type' => Report::TYPE_COMPORTEMENT,
                'description' => 'L\'utilisateur a créé plusieurs faux comptes pour contourner les règles de la plateforme. Cela va à l\'encontre des conditions d\'utilisation.',
                'status' => Report::STATUS_REJETE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => $users->random()->id,
                'exchange_id' => null,
            ],
            [
                'type' => Report::TYPE_CONFLIT_ECHANGE,
                'description' => 'L\'échange s\'est mal passé. L\'autre personne a annulé au dernier moment sans préavis, me laissant sans solution pour récupérer le livre dont j\'avais besoin.',
                'status' => Report::STATUS_EN_ATTENTE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => null,
                'exchange_id' => $exchanges->isNotEmpty() ? $exchanges->random()->id : null,
            ],
            [
                'type' => Report::TYPE_COMPORTEMENT,
                'description' => 'Cet utilisateur publie des annonces trompeuses. Les livres qu\'il propose ne correspondent jamais à la description donnée.',
                'status' => Report::STATUS_TRAITE,
                'reporter_id' => $users->random()->id,
                'reported_user_id' => $users->random()->id,
                'exchange_id' => null,
            ],
        ];

        foreach ($sampleReports as $reportData) {
            // Make sure reporter and reported user are different
            while ($reportData['reported_user_id'] && $reportData['reporter_id'] === $reportData['reported_user_id']) {
                $reportData['reported_user_id'] = $users->random()->id;
            }

            Report::create($reportData);
        }

        // Create additional random reports
        if ($exchanges->isNotEmpty()) {
            // Exchange conflict reports
            Report::factory(5)->exchangeConflict()->create([
                'exchange_id' => $exchanges->random()->id,
            ]);

            // Behavior reports
            Report::factory(8)->behavior()->create();

            // Mixed status reports
            Report::factory(3)->pending()->create();
            Report::factory(4)->processed()->create();
            Report::factory(2)->rejected()->create();
        } else {
            // Only behavior reports if no exchanges exist
            Report::factory(15)->behavior()->create();
        }

        $this->command->info('Created ' . Report::count() . ' reports successfully!');
        
        // Show statistics
        $stats = [
            'Total' => Report::count(),
            'En attente' => Report::pending()->count(),
            'Traités' => Report::processed()->count(),
            'Rejetés' => Report::rejected()->count(),
            'Conflits d\'échange' => Report::where('type', Report::TYPE_CONFLIT_ECHANGE)->count(),
            'Comportements' => Report::where('type', Report::TYPE_COMPORTEMENT)->count(),
        ];

        $this->command->table(['Type', 'Nombre'], collect($stats)->map(fn($count, $type) => [$type, $count])->toArray());
    }
}