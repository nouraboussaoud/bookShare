<?php

namespace App\Http\Controllers;

use App\Models\ReservationPayment;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = ReservationPayment::with(['location.book', 'location.proprietaire', 'location.locataire'])
            ->latest()
            ->paginate(15);

        return view('reservation-payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $locationId = $request->query('location_id');
        $location = null;
        
        if ($locationId) {
            $location = Location::findOrFail($locationId);
            // Vérifier les permissions
            if ($location->proprietaire_id !== Auth::id() && $location->locataire_id !== Auth::id()) {
                abort(403, 'Accès non autorisé');
            }
        }

        $locations = Location::where(function($query) {
                $query->where('proprietaire_id', Auth::id())
                      ->orWhere('locataire_id', Auth::id());
            })
            ->whereIn('statut', ['en_attente', 'confirmee', 'en_cours', 'terminee'])
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservation-payments.create', compact('locations', 'location'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'montant' => 'required|numeric|min:0',
            'type_paiement' => 'required|in:caution,location,penalite,remboursement',
            'statut_paiement' => 'required|in:en_attente,complete,echoue,rembourse,annule',
            'methode_paiement' => 'nullable|string|max:255',
            'reference_transaction' => 'nullable|string|max:255',
            'date_paiement' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        // Vérifier les permissions
        $location = Location::findOrFail($validated['location_id']);
        if ($location->proprietaire_id !== Auth::id() && $location->locataire_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $payment = ReservationPayment::create($validated);

        return redirect()->route('reservation-payments.show', $payment)
            ->with('success', 'Paiement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservationPayment $reservationPayment)
    {
        $reservationPayment->load(['location.book', 'location.proprietaire', 'location.locataire']);

        // Vérifier les permissions
        if ($reservationPayment->location->proprietaire_id !== Auth::id() && 
            $reservationPayment->location->locataire_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('reservation-payments.show', compact('reservationPayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservationPayment $reservationPayment)
    {
        $reservationPayment->load('location');

        // Vérifier les permissions
        if ($reservationPayment->location->proprietaire_id !== Auth::id() && 
            $reservationPayment->location->locataire_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $locations = Location::where(function($query) {
                $query->where('proprietaire_id', Auth::id())
                      ->orWhere('locataire_id', Auth::id());
            })
            ->whereIn('statut', ['en_attente', 'confirmee', 'en_cours', 'terminee'])
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservation-payments.edit', compact('reservationPayment', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservationPayment $reservationPayment)
    {
        // Vérifier les permissions
        if ($reservationPayment->location->proprietaire_id !== Auth::id() && 
            $reservationPayment->location->locataire_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'montant' => 'required|numeric|min:0',
            'type_paiement' => 'required|in:caution,location,penalite,remboursement',
            'statut_paiement' => 'required|in:en_attente,complete,echoue,rembourse,annule',
            'methode_paiement' => 'nullable|string|max:255',
            'reference_transaction' => 'nullable|string|max:255',
            'date_paiement' => 'nullable|date',
            'date_remboursement' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $reservationPayment->update($validated);

        return redirect()->route('reservation-payments.show', $reservationPayment)
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservationPayment $reservationPayment)
    {
        // Vérifier les permissions - Seulement le propriétaire peut supprimer
        if ($reservationPayment->location->proprietaire_id !== Auth::id()) {
            abort(403, 'Seul le propriétaire peut supprimer ce paiement');
        }

        $reservationPayment->delete();

        return redirect()->route('reservation-payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Marquer un paiement comme complet
     */
    public function marquerComplete(ReservationPayment $reservationPayment)
    {
        // Vérifier les permissions
        if ($reservationPayment->location->proprietaire_id !== Auth::id()) {
            abort(403, 'Seul le propriétaire peut marquer le paiement comme complet');
        }

        $reservationPayment->marquerCommeComplete();

        return redirect()->back()->with('success', 'Paiement marqué comme complet.');
    }

    /**
     * Rembourser un paiement
     */
    public function rembourser(ReservationPayment $reservationPayment)
    {
        // Vérifier les permissions
        if ($reservationPayment->location->proprietaire_id !== Auth::id()) {
            abort(403, 'Seul le propriétaire peut rembourser ce paiement');
        }

        $reservationPayment->rembourser();

        return redirect()->back()->with('success', 'Paiement remboursé avec succès.');
    }

    /**
     * Afficher les paiements d'une réservation spécifique
     */
    public function byLocation(Location $location)
    {
        // Vérifier les permissions
        if ($location->proprietaire_id !== Auth::id() && $location->locataire_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $payments = $location->payments()->latest()->get();

        return view('reservation-payments.by-location', compact('location', 'payments'));
    }
}
