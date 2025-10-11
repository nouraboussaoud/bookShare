<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class FixLocationDurationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:fix-duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix location duration values to ensure they are integers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing location duration values...');

        // Récupérer toutes les locations pour vérifier leurs durées
        $locations = Location::all();

        if ($locations->isEmpty()) {
            $this->info('No locations found with invalid duration values.');
            return Command::SUCCESS;
        }

        $fixedCount = 0;

        foreach ($locations as $location) {
            $originalDuration = $location->duree_jours;
            
            // Vérifier si la durée n'est pas un entier valide
            if (!is_int($originalDuration) || $originalDuration < 1 || $originalDuration > 90) {
                // Convertir en entier, défaut à 7 jours si invalide
                $newDuration = is_numeric($originalDuration) ? (int) $originalDuration : 7;
                
                // S'assurer que la durée est dans une plage raisonnable
                if ($newDuration < 1) $newDuration = 1;
                if ($newDuration > 90) $newDuration = 90;

                $location->duree_jours = $newDuration;
                $location->save();

                $this->line("Location ID {$location->id}: '{$originalDuration}' → {$newDuration}");
                $fixedCount++;
            }
        }

        $this->info("Fixed {$fixedCount} location duration values.");

        // Recalculer les dates de fin pour toutes les locations en attente ou confirmées
        $this->info('Recalculating end dates for active locations...');
        
        $activeLocations = Location::whereIn('statut', ['en_attente', 'confirmee', 'en_cours'])->get();
        
        foreach ($activeLocations as $location) {
            $location->calculerDateFin();
            $location->save();
        }

        $this->info("Recalculated end dates for {$activeLocations->count()} active locations.");

        return Command::SUCCESS;
    }
}
