<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use Carbon\Carbon;

class UpdateReportsPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Mettre à jour les signalements existants avec leurs scores de priorité
     */
    public function run()
    {
        $this->command->info('📊 Mise à jour des priorités des signalements...');

        // Récupérer tous les signalements
        $reports = Report::all();
        
        if ($reports->isEmpty()) {
            $this->command->warn('⚠️  Aucun signalement trouvé dans la base de données.');
            return;
        }

        $updated = 0;
        
        foreach ($reports as $report) {
            // Compter les signalements similaires
            $report->similar_reports_count = $report->countSimilarReports();
            
            // Vérifier si récidiviste
            $report->is_recurring_offender = $report->checkIfRecurringOffender();
            
            // Calculer le score de priorité
            $report->priority_score = $report->calculatePriorityScore();
            
            // Déterminer le niveau de priorité
            $score = $report->priority_score;
            if ($score >= 8) {
                $report->priority_level = Report::PRIORITY_CRITIQUE;
            } elseif ($score >= 6) {
                $report->priority_level = Report::PRIORITY_HAUTE;
            } elseif ($score >= 4) {
                $report->priority_level = Report::PRIORITY_MOYENNE;
            } else {
                $report->priority_level = Report::PRIORITY_NORMALE;
            }
            
            $report->save();
            $updated++;
            
            $this->command->info("✅ Signalement #{$report->id}: {$report->priority_icon} {$report->priority_level} (Score: {$report->priority_score}/10)");
        }

        $this->command->info("\n✅ {$updated} signalements mis à jour avec succès !");
        
        // Afficher les statistiques
        $this->displayStatistics();
    }

    /**
     * Afficher les statistiques des priorités
     */
    private function displayStatistics()
    {
        $this->command->info("\n📈 Statistiques des priorités :");
        
        $critique = Report::where('priority_level', Report::PRIORITY_CRITIQUE)->count();
        $haute = Report::where('priority_level', Report::PRIORITY_HAUTE)->count();
        $moyenne = Report::where('priority_level', Report::PRIORITY_MOYENNE)->count();
        $normale = Report::where('priority_level', Report::PRIORITY_NORMALE)->count();
        
        $this->command->table(
            ['Priorité', 'Nombre', 'Icône'],
            [
                ['Critique', $critique, '🔴'],
                ['Haute', $haute, '🟠'],
                ['Moyenne', $moyenne, '🟡'],
                ['Normale', $normale, '🟢'],
            ]
        );
        
        $recurringOffenders = Report::where('is_recurring_offender', true)
            ->distinct('reported_user_id')
            ->count('reported_user_id');
        
        $this->command->info("⚠️  Récidivistes identifiés : {$recurringOffenders}");
        
        $pending = Report::pending()->count();
        $processed = Report::processed()->count();
        
        $this->command->info("⏳ En attente : {$pending}");
        $this->command->info("✅ Traités : {$processed}");
    }
}
