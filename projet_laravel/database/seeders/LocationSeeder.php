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
            $this->command->warn('Pas assez d\'utilisateurs ou de livres pour créer des locations.');
            return;
        }

        // Localisations variées
        $localisations = [
            'Bibliothèque Centrale',
            'Café du Livre',
            'Parc Municipal',
            'Librairie Indépendante',
            'Centre Commercial',
            'Université - Campus',
            'Gare SNCF',
            'Place de la Mairie',
            'Café des Arts',
            'Bibliothèque Universitaire',
            'Station de Métro',
            'Domicile du propriétaire',
            'Domicile du locataire',
            'Marché du Livre',
            'Salle Polyvalente'
        ];

        // Notes variées selon le statut
        $notesEnAttente = [
            'Intéressé par ce livre, merci de confirmer rapidement.',
            'Première location, j\'espère que tout se passera bien.',
            'Urgent : besoin du livre pour mes études.',
            'Fan de cet auteur, hâte de le lire !',
            'Recommandé par un ami, j\'aimerais le découvrir.',
        ];

        $notesConfirmee = [
            'Location confirmée, rendez-vous prévu.',
            'Merci pour la confirmation rapide !',
            'Hâte de récupérer le livre.',
            'Rendez-vous fixé pour l\'échange.',
            'Tout est prêt pour la location.',
        ];

        $notesEnCours = [
            'Lecture en cours, très captivant !',
            'Super livre, merci pour le prêt.',
            'Je prends bien soin du livre.',
            'Lecture commencée, c\'est passionnant.',
            'Excellent état du livre, merci.',
        ];

        $notesTerminee = [
            'Livre retourné en excellent état, merci !',
            'Transaction parfaite, recommandé !',
            'Merci pour cette belle lecture.',
            'Livre rendu en main propre, tout s\'est bien passé.',
            'Expérience positive, à refaire !',
        ];

        $notesAnnulee = [
            'Annulation demandée par le locataire.',
            'Le livre n\'était plus disponible.',
            'Changement de programme, désolé.',
            'Livre trouvé ailleurs entre-temps.',
            'Désistement mutuel.',
        ];

        $locationsCount = 0;
        
        // Créer entre 0-4 locations par livre (en évitant les conflits)
        foreach ($books as $book) {
            $numLocations = rand(0, 4);
            
            for ($i = 0; $i < $numLocations; $i++) {
                // Choisir un locataire différent du propriétaire
                $potentialLocataires = $users->where('id', '!=', $book->user_id);
                
                if ($potentialLocataires->isEmpty()) {
                    continue;
                }
                
                $locataire = $potentialLocataires->random();
                
                // Répartition réaliste des statuts
                $rand = rand(1, 100);
                if ($rand <= 20) {
                    $statut = 'en_attente';
                    $notes = $notesEnAttente[array_rand($notesEnAttente)];
                } elseif ($rand <= 35) {
                    $statut = 'confirmee';
                    $notes = $notesConfirmee[array_rand($notesConfirmee)];
                } elseif ($rand <= 60) {
                    $statut = 'en_cours';
                    $notes = $notesEnCours[array_rand($notesEnCours)];
                } elseif ($rand <= 90) {
                    $statut = 'terminee';
                    $notes = $notesTerminee[array_rand($notesTerminee)];
                } else {
                    $statut = 'annulee';
                    $notes = $notesAnnulee[array_rand($notesAnnulee)];
                }

                // Prix aléatoire entre 2€ et 15€
                $prix = rand(2, 15) + (rand(0, 1) * 0.5);
                
                // Durée entre 3 et 30 jours
                $dureeJours = rand(3, 30);
                
                // Date de location selon le statut
                if ($statut === 'en_attente' || $statut === 'confirmee') {
                    $dateLocation = Carbon::now()->addDays(rand(1, 14));
                } elseif ($statut === 'en_cours') {
                    $dateLocation = Carbon::now()->subDays(rand(1, $dureeJours - 1));
                } else { // terminee ou annulee
                    $dateLocation = Carbon::now()->subDays(rand($dureeJours + 1, 60));
                }

                // Créer la location
                $location = new Location();
                $location->book_id = $book->id;
                $location->proprietaire_id = $book->user_id;
                $location->locataire_id = $locataire->id;
                $location->date_location = $dateLocation;
                $location->duree_jours = $dureeJours;
                $location->localisation = $localisations[array_rand($localisations)];
                $location->prix = $prix;
                $location->statut = $statut;
                $location->notes = $notes;
                
                // Date de retour effective pour les locations terminées
                if ($statut === 'terminee') {
                    // 80% retournés à temps, 20% en retard
                    if (rand(1, 100) <= 80) {
                        $location->date_retour_effective = Carbon::parse($dateLocation)->addDays(rand(1, $dureeJours));
                    } else {
                        $location->date_retour_effective = Carbon::parse($dateLocation)->addDays($dureeJours + rand(1, 7));
                    }
                }
                
                // Calculer la date de fin
                $location->calculerDateFin();
                $location->save();
                
                $locationsCount++;
            }
        }

        $this->command->info("✓ Created {$locationsCount} locations successfully!");
        
        // Afficher les statistiques
        $stats = [
            ['Statut', 'Nombre'],
            ['Total', $locationsCount],
            ['En attente', Location::where('statut', 'en_attente')->count()],
            ['Confirmée', Location::where('statut', 'confirmee')->count()],
            ['En cours', Location::where('statut', 'en_cours')->count()],
            ['Terminée', Location::where('statut', 'terminee')->count()],
            ['Annulée', Location::where('statut', 'annulee')->count()],
        ];
        
        $this->command->table($stats[0], array_slice($stats, 1));
    }
}
