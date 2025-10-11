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
        // Récupérer quelques utilisateurs et livres existants
        $users = User::all();
        $books = Book::all();

        if ($users->count() < 2 || $books->count() < 1) {
            $this->command->info('Pas assez d\'utilisateurs ou de livres pour créer des locations de test.');
            return;
        }

        // Créer des locations d'exemple
        $locations = [
            [
                'book_id' => $books->first()->id,
                'proprietaire_id' => $books->first()->user_id,
                'locataire_id' => $users->where('id', '!=', $books->first()->user_id)->first()->id,
                'date_location' => Carbon::now()->addDays(1),
                'duree_jours' => 14,
                'localisation' => 'Bibliothèque Centrale',
                'prix' => 5.00,
                'statut' => 'en_attente',
                'notes' => 'Première demande de location pour tester le système.'
            ],
            [
                'book_id' => $books->skip(1)->first()->id ?? $books->first()->id,
                'proprietaire_id' => $books->skip(1)->first()->user_id ?? $books->first()->user_id,
                'locataire_id' => $users->where('id', '!=', ($books->skip(1)->first()->user_id ?? $books->first()->user_id))->first()->id,
                'date_location' => Carbon::now()->subDays(5),
                'duree_jours' => 7,
                'localisation' => 'Café du Coin',
                'prix' => 3.50,
                'statut' => 'en_cours',
                'notes' => 'Location en cours pour tester le suivi.'
            ],
            [
                'book_id' => $books->skip(2)->first()->id ?? $books->first()->id,
                'proprietaire_id' => $books->skip(2)->first()->user_id ?? $books->first()->user_id,
                'locataire_id' => $users->where('id', '!=', ($books->skip(2)->first()->user_id ?? $books->first()->user_id))->skip(1)->first()->id ?? $users->first()->id,
                'date_location' => Carbon::now()->subDays(20),
                'duree_jours' => 10,
                'localisation' => 'Parc Municipal',
                'prix' => 4.00,
                'statut' => 'terminee',
                'notes' => 'Location terminée avec succès.',
                'date_retour_effective' => Carbon::now()->subDays(10)
            ]
        ];

        foreach ($locations as $locationData) {
            $location = new Location();
            $location->book_id = $locationData['book_id'];
            $location->proprietaire_id = $locationData['proprietaire_id'];
            $location->locataire_id = $locationData['locataire_id'];
            $location->date_location = $locationData['date_location'];
            $location->duree_jours = $locationData['duree_jours'];
            $location->localisation = $locationData['localisation'];
            $location->prix = $locationData['prix'];
            $location->statut = $locationData['statut'];
            $location->notes = $locationData['notes'];
            
            if (isset($locationData['date_retour_effective'])) {
                $location->date_retour_effective = $locationData['date_retour_effective'];
            }
            
            // Calculer la date de fin
            $location->calculerDateFin();
            $location->save();
        }

        $this->command->info('Locations de test créées avec succès !');
    }
}
