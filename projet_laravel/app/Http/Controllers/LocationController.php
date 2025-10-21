<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Book;
use App\Models\User;
use App\Models\ReservationPayment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Locations où l'utilisateur est propriétaire
        $locationsCommeProprietaire = Location::with(['book', 'locataire'])
            ->where('proprietaire_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Locations où l'utilisateur est locataire
        $locationsCommeLocataire = Location::with(['book', 'proprietaire'])
            ->where('locataire_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('locations.index', compact('locationsCommeProprietaire', 'locationsCommeLocataire'));
    }

    /**
     * Afficher tous les livres disponibles à la location
     */
    public function marketplace(Request $request)
    {
        $query = Book::with(['user', 'category', 'rentalOffer'])
            ->where('status', 'available')
            ->whereDoesntHave('locations', function($query) {
                $query->whereIn('statut', ['confirmee', 'en_cours']);
            })
            ->whereHas('rentalOffer', function($query) {
                $query->where('is_active', true);
            });

        // Filtrage par recherche (titre ou auteur)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
            });
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        // Filtrer par prix maximum
        if ($request->filled('price_max')) {
            $priceMax = (float) $request->get('price_max');
            $query->whereHas('rentalOffer', function($q) use ($priceMax) {
                $q->where('prix_par_jour', '<=', $priceMax);
            });
        }

        // Récupérer les livres avec pagination
        $livresDisponibles = $query->orderBy('created_at', 'desc')->paginate(12);

        // Récupérer les locations récentes pour avoir des exemples de prix
        $locationsRecentes = Location::with(['book', 'proprietaire'])
            ->whereIn('statut', ['confirmee', 'en_cours', 'terminee'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Récupérer les catégories pour le filtre
        $categories = \App\Models\Category::where('is_active', true)->get();

        return view('locations.marketplace', compact('livresDisponibles', 'locationsRecentes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $book_id = $request->get('book_id');
        $book = null;
        
        if ($book_id) {
            $book = Book::with('user')->findOrFail($book_id);
            
            // Vérifier que le livre n'appartient pas à l'utilisateur connecté
            if ($book->user_id === Auth::id()) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas louer votre propre livre.');
            }
            
            // Vérifier que le livre est disponible
            if (!$book->estDisponiblePourLocation()) {
                return redirect()->back()->with('error', 'Ce livre n\'est pas disponible pour la location.');
            }
        }
        
        // Récupérer tous les livres disponibles à la location (qui n'appartiennent pas à l'utilisateur)
        $livresDisponibles = Book::with(['user', 'category'])
            ->where('status', 'available')
            ->where('user_id', '!=', Auth::id())
            ->whereDoesntHave('locations', function($query) {
                $query->whereIn('statut', ['confirmee', 'en_cours']);
            })
            ->orderBy('title', 'asc')
            ->get();
        
        return view('locations.create', compact('book', 'livresDisponibles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'date_location' => 'required|date|after_or_equal:today',
            'duree_jours' => 'required|integer|min:1|max:90',
            'localisation' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $book = Book::findOrFail($request->book_id);
        
        // Vérifications de sécurité
        if ($book->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas louer votre propre livre.');
        }
        
        if (!$book->estDisponiblePourLocation()) {
            return redirect()->back()->with('error', 'Ce livre n\'est pas disponible pour la location.');
        }

        $location = new Location();
        $location->book_id = $request->book_id;
        $location->proprietaire_id = $book->user_id;
        $location->locataire_id = Auth::id();
        $location->date_location = $request->date_location;
        $location->duree_jours = $request->duree_jours;
        $location->localisation = $request->localisation;
        $location->prix = $request->prix;
        $location->notes = $request->notes;
        $location->statut = 'en_attente';
        
        // Calculer la date de fin
        $location->calculerDateFin();
        $location->save();

        // Envoyer une notification au propriétaire
        $notificationService = new NotificationService();
        $notificationService->notifyOwnerOfLocationRequest($location);

        return redirect()->route('locations.show', $location)
            ->with('success', 'Demande de location créée avec succès. En attente de confirmation du propriétaire.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        // Vérifier que l'utilisateur a le droit de voir cette location
        if ($location->proprietaire_id !== Auth::id() && $location->locataire_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $location->load(['book', 'proprietaire', 'locataire']);
        
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        // Seul le locataire peut modifier une location en attente
        if ($location->locataire_id !== Auth::id() || $location->statut !== 'en_attente') {
            abort(403, 'Vous ne pouvez pas modifier cette location.');
        }
        
        $location->load('book');
        
        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        // Seul le locataire peut modifier une location en attente
        if ($location->locataire_id !== Auth::id() || $location->statut !== 'en_attente') {
            abort(403, 'Vous ne pouvez pas modifier cette location.');
        }
        
        $request->validate([
            'date_location' => 'required|date|after_or_equal:today',
            'duree_jours' => 'required|integer|min:1|max:90',
            'localisation' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $location->update([
            'date_location' => $request->date_location,
            'duree_jours' => $request->duree_jours,
            'localisation' => $request->localisation,
            'prix' => $request->prix,
            'notes' => $request->notes,
        ]);
        
        // Recalculer la date de fin
        $location->calculerDateFin();
        $location->save();

        return redirect()->route('locations.show', $location)
            ->with('success', 'Location mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        // Seul le locataire peut supprimer une location en attente
        if ($location->locataire_id !== Auth::id() || $location->statut !== 'en_attente') {
            abort(403, 'Vous ne pouvez pas supprimer cette location.');
        }
        
        $location->delete();
        
        return redirect()->route('locations.index')
            ->with('success', 'Demande de location supprimée avec succès.');
    }
    
    /**
     * Confirmer une location (propriétaire)
     */
    public function confirmer(Location $location)
    {
        if ($location->proprietaire_id !== Auth::id() || $location->statut !== 'en_attente') {
            abort(403, 'Vous ne pouvez pas confirmer cette location.');
        }
        
        $location->statut = 'confirmee';
        $location->save();

        // Créer automatiquement un paiement
        $payment = ReservationPayment::create([
            'location_id' => $location->id,
            'montant' => $location->prix,
            'type_paiement' => 'location',
            'statut_paiement' => 'en_attente',
        ]);

        // Envoyer notification au locataire qu'il doit payer
        $notificationService = new NotificationService();
        $notificationService->notifyTenantLocationAccepted($location, $payment);
        
        return redirect()->route('locations.show', $location)
            ->with('success', 'Location confirmée avec succès. Le locataire a été notifié pour effectuer le paiement.');
    }
    
    /**
     * Refuser une location (propriétaire)
     */
    public function refuser(Location $location)
    {
        if ($location->proprietaire_id !== Auth::id() || $location->statut !== 'en_attente') {
            abort(403, 'Vous ne pouvez pas refuser cette location.');
        }
        
        $location->statut = 'annulee';
        $location->save();

        // Notifier le locataire du refus
        $notificationService = new NotificationService();
        $notificationService->notifyTenantLocationRejected($location);
        
        return redirect()->route('locations.show', $location)
            ->with('success', 'Location refusée. Le locataire a été notifié.');
    }
    
    /**
     * Démarrer une location (propriétaire)
     */
    public function demarrer(Location $location)
    {
        if ($location->proprietaire_id !== Auth::id() || $location->statut !== 'confirmee') {
            abort(403, 'Vous ne pouvez pas démarrer cette location.');
        }

        // Vérifier que le paiement a été effectué
        $payment = $location->payments()->where('statut_paiement', 'complete')->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Le locataire doit d\'abord effectuer le paiement.');
        }
        
        $location->statut = 'en_cours';
        $location->save();

        // Notifier les deux parties
        $notificationService = new NotificationService();
        $notificationService->notifyTenantLocationStarted($location);
        $notificationService->notifyOwnerLocationStarted($location);
        
        return redirect()->route('locations.show', $location)
            ->with('success', 'Location démarrée avec succès. Les deux parties ont été notifiées.');
    }
    
    /**
     * Terminer une location (propriétaire)
     */
    public function terminer(Location $location)
    {
        if ($location->proprietaire_id !== Auth::id() || $location->statut !== 'en_cours') {
            abort(403, 'Vous ne pouvez pas terminer cette location.');
        }
        
        $location->marquerCommeTerminee();

        // Notifier les deux parties
        $notificationService = new NotificationService();
        $notificationService->notifyTenantLocationCompleted($location);
        $notificationService->notifyOwnerLocationCompleted($location);
        
        return redirect()->route('locations.show', $location)
            ->with('success', 'Location terminée avec succès. Les deux parties ont été notifiées.');
    }
}
