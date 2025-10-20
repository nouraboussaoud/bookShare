<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationAdminController extends Controller
{
    /**
     * Display a listing of all locations (reservations).
     */
    public function index(Request $request)
    {
        $query = Location::with(['locataire', 'book', 'proprietaire'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('locataire', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function($q) use ($search) {
                    $q->where('titre', 'like', "%{$search}%");
                });
            });
        }

        $locations = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Location::count(),
            'en_attente' => Location::where('statut', 'en_attente')->count(),
            'confirmee' => Location::where('statut', 'confirmee')->count(),
            'en_cours' => Location::where('statut', 'en_cours')->count(),
            'terminee' => Location::where('statut', 'terminee')->count(),
            'refusee' => Location::where('statut', 'refusee')->count(),
        ];

        return view('admin.locations.index', compact('locations', 'stats'));
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        $location->load(['locataire', 'book', 'proprietaire']);
        
        return view('admin.locations.show', compact('location'));
    }

    /**
     * Approve a pending location.
     */
    public function approve(Location $location)
    {
        if ($location->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être approuvée.');
        }

        $location->update([
            'statut' => 'confirmee'
        ]);

        return redirect()->back()->with('success', 'Réservation approuvée avec succès.');
    }

    /**
     * Reject a pending location.
     */
    public function reject(Location $location)
    {
        if ($location->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être rejetée.');
        }

        $location->update([
            'statut' => 'refusee'
        ]);

        return redirect()->back()->with('success', 'Réservation rejetée avec succès.');
    }

    /**
     * Remove the specified location.
     */
    public function destroy(Location $location)
    {
        try {
            $location->delete();
            
            return redirect()->route('admin.locations.index')
                ->with('success', 'Réservation supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la réservation.');
        }
    }
}
