<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\RentalOffer;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de création d'offre de location
     */
    public function create($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Vérifier que c'est bien le propriétaire
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas le propriétaire de ce livre.');
        }

        // Vérifier si une offre existe déjà
        $existingOffer = $book->rentalOffer;

        return view('rental-offers.create', compact('book', 'existingOffer'));
    }

    /**
     * Enregistrer ou mettre à jour l'offre de location
     */
    public function store(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);

        // Vérifier que c'est bien le propriétaire
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas le propriétaire de ce livre.');
        }

        $validated = $request->validate([
            'prix_par_jour' => 'required|numeric|min:0|max:999.99',
            'localisation' => 'required|string|max:255',
            'duree_min_jours' => 'required|integer|min:1|max:365',
            'duree_max_jours' => 'required|integer|min:1|max:365',
            'conditions' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['book_id'] = $book->id;
        $validated['is_active'] = true;

        // Créer ou mettre à jour l'offre
        $offer = RentalOffer::updateOrCreate(
            ['book_id' => $book->id],
            $validated
        );

        return redirect()
            ->route('locations.marketplace')
            ->with('success', 'Votre offre de location a été créée ! Votre livre est maintenant visible sur le marketplace.');
    }

    /**
     * Location en 1 clic (sans formulaire)
     */
    public function rentNow($offerId)
    {
        $offer = RentalOffer::with(['book', 'user'])->findOrFail($offerId);

        // Vérifier que l'offre est active
        if (!$offer->is_active) {
            return redirect()
                ->back()
                ->with('error', 'Cette offre n\'est plus active.');
        }

        // Vérifier que ce n'est pas le propriétaire
        if ($offer->user_id === Auth::id()) {
            return redirect()
                ->back()
                ->with('error', 'Vous ne pouvez pas louer votre propre livre.');
        }

        // Vérifier que le livre est disponible
        if ($offer->book->status !== 'available') {
            return redirect()
                ->back()
                ->with('error', 'Ce livre n\'est plus disponible.');
        }

        // Créer la location automatiquement avec les infos de l'offre
        // Durée par défaut : duree_min_jours
        $dureeJours = $offer->duree_min_jours;
        $dateDebut = now();
        $dateFin = now()->addDays($dureeJours);
        $prix = $offer->calculatePrice($dureeJours);

        $location = Location::create([
            'book_id' => $offer->book_id,
            'proprietaire_id' => $offer->user_id,
            'locataire_id' => Auth::id(),
            'date_location' => $dateDebut,
            'duree_jours' => $dureeJours,
            'date_fin_prevue' => $dateFin,
            'localisation' => $offer->localisation,
            'prix' => $prix,
            'statut' => 'en_attente',
            'notes' => 'Demande créée automatiquement depuis l\'offre de location'
        ]);

        // Envoyer notification au propriétaire
        \App\Models\Notification::create([
            'user_id' => $location->proprietaire_id,
            'type' => 'location_request',
            'title' => 'Nouvelle demande de location',
            'message' => $location->locataire->name . ' souhaite louer "' . $location->book->title . '"',
            'data' => json_encode([
                'location_id' => $location->id,
                'book_title' => $location->book->title,
                'locataire_name' => $location->locataire->name,
            ]),
            'read_at' => null
        ]);

        return redirect()
            ->route('locations.show', $location)
            ->with('success', 'Votre demande de location a été envoyée au propriétaire ! Vous serez notifié dès qu\'il l\'acceptera.');
    }

    /**
     * Désactiver une offre
     */
    public function deactivate($offerId)
    {
        $offer = RentalOffer::findOrFail($offerId);

        if ($offer->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas le propriétaire de cette offre.');
        }

        $offer->update(['is_active' => false]);

        return redirect()
            ->back()
            ->with('success', 'Votre offre a été désactivée.');
    }

    /**
     * Activer une offre
     */
    public function activate($offerId)
    {
        $offer = RentalOffer::findOrFail($offerId);

        if ($offer->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas le propriétaire de cette offre.');
        }

        $offer->update(['is_active' => true]);

        return redirect()
            ->back()
            ->with('success', 'Votre offre a été réactivée.');
    }
}
