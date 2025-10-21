<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Locations...');

        // Récupérer les utilisateurs et livres existants
        $users = User::all();
        $books = Book::all();

        if ($users->count() < 2 || $books->count() < 1) {
            $this->command->warn('Pas assez d\'utilisateurs ou de livres pour créer des locations de test.');
            return;
        }

        $localisations = [
            'Bibliothèque Centrale',
            'Café du Coin',
            'Parc Municipal',
            'Centre Commercial Les Halles',
            'Station de Métro République',
            'Place de la Mairie',
            'Librairie du Centre',
            'Starbucks Downtown',
            'McDonald\'s Gare',
            'Université - Campus Principal',
            'Centre Culturel',
            'À domicile - Chez le propriétaire',
            'À domicile - Chez le locataire',
            'Bureau de Poste Central',
            'Salle Polyvalente',
        ];

        $notesExamples = [
            'J\'aimerais lire ce livre pour mes études.',
            'Très intéressé par ce titre, merci !',
            'Besoin urgent pour un projet scolaire.',
            'Je cherchais ce livre depuis longtemps.',
            'Lecture pour le club de lecture.',
            'Recommandé par un ami.',
            'Pour des vacances relaxantes.',
            'Cadeau pour un proche.',
            'Préparation d\'un exposé.',
            'Simple curiosité littéraire.',
            null, // Certaines sans notes
            null,
            null,
        ];

        $count = 0;
        $statuts = [
            'en_attente' => 0,
            'confirmee' => 0,
            'en_cours' => 0,
            'terminee' => 0,
            'annulee' => 0,
        ];

        // Créer des locations variées pour chaque livre
        foreach ($books as $book) {
            // Nombre aléatoire de locations par livre (0-4)
            $locationCount = rand(0, 4);
            
            for ($i = 0; $i < $locationCount; $i++) {
                // Trouver un locataire différent du propriétaire
                $potentialTenants = $users->where('id', '!=', $book->user_id);
                if ($potentialTenants->isEmpty()) {
                    continue;
                }

                $locataire = $potentialTenants->random();
                
                // Répartition des statuts
                $statutWeights = [
                    'en_attente' => 20,   // 20%
                    'confirmee' => 15,    // 15%
                    'en_cours' => 25,     // 25%
                    'terminee' => 30,     // 30%
                    'annulee' => 10,      // 10%
                ];
                
                $rand = rand(1, 100);
                $cumulative = 0;
                $statut = 'en_attente';
                
                foreach ($statutWeights as $status => $weight) {
                    $cumulative += $weight;
                    if ($rand <= $cumulative) {
                        $statut = $status;
                        break;
                    }
                }

                // Dates selon le statut
                $dateLocation = null;
                $dateRetour = null;
                $dureeJours = rand(3, 30);
                
                switch ($statut) {
                    case 'en_attente':
                        $dateLocation = Carbon::now()->addDays(rand(1, 10));
                        break;
                    case 'confirmee':
                        $dateLocation = Carbon::now()->addDays(rand(1, 5));
                        break;
                    case 'en_cours':
                        $dateLocation = Carbon::now()->subDays(rand(1, 20));
                        break;
                    case 'terminee':
                        $dateLocation = Carbon::now()->subDays(rand(20, 90));
                        $dateRetour = Carbon::parse($dateLocation)->addDays($dureeJours)->subDays(rand(-2, 2));
                        break;
                    case 'annulee':
                        $dateLocation = Carbon::now()->addDays(rand(-10, 10));
                        break;
                }

                $location = new Location();
                $location->book_id = $book->id;
                $location->proprietaire_id = $book->user_id;
                $location->locataire_id = $locataire->id;
                $location->date_location = $dateLocation;
                $location->duree_jours = $dureeJours;
                $location->localisation = $localisations[array_rand($localisations)];
                $location->prix = round(rand(200, 1500) / 100, 2); // Entre 2€ et 15€
                $location->statut = $statut;
                $location->notes = $notesExamples[array_rand($notesExamples)];
                
                if ($dateRetour) {
                    $location->date_retour_effective = $dateRetour;
                }
                
                // Calculer la date de fin
                $location->calculerDateFin();
                $location->save();
                
                $count++;
                $statuts[$statut]++;
            }
        }

        $this->command->info("✓ Créé $count locations avec succès!");
        $this->command->newLine();
        $this->command->table(
            ['Statut', 'Nombre'],
            [
                ['En attente', $statuts['en_attente']],
                ['Confirmée', $statuts['confirmee']],
                ['En cours', $statuts['en_cours']],
                ['Terminée', $statuts['terminee']],
                ['Annulée', $statuts['annulee']],
                ['TOTAL', $count],
            ]
        );
    }
}
