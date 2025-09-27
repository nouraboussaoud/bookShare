<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    // Display a listing of exchanges
    public function index()
    {
        // Show exchanges where user is either initiator or receiver
        $exchanges = Exchange::with(['initiateur', 'recepteur', 'bookDemande.user'])
            ->where(function($query) {
                $query->where('userInitiateurId', Auth::id())
                      ->orWhere('userRecepteurId', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exchanges.index', compact('exchanges'));
    }

    // Réserver un livre
    public function reserveBook(Request $request)
    {
        $validated = $request->validate([
            'bookDemandeId' => 'required|exists:books,id',
        ]);

        $exchange = Exchange::create([
            'type' => 'RESERVATION',
            'status' => 'EN_ATTENTE',
            'dateDebut' => now(),
            'dateFin' => now()->addDays(7),
            'userInitiateurId' => Auth::id(),
            'userRecepteurId' => null,
            'bookDemandeId' => $validated['bookDemandeId'],
        ]);

        return response()->json(['message' => 'Réservation créée avec succès.', 'exchange' => $exchange], 201);
    }

    // Confirmer un échange
    public function confirmExchange(Request $request, $id)
    {
        $exchange = Exchange::findOrFail($id);
        $oldStatus = $exchange->status;

        if ($exchange->status === 'EN_ATTENTE') {
            $exchange->status = 'EN_COURS';
        } elseif ($exchange->status === 'EN_COURS') {
            $exchange->status = 'TERMINE';
        }

        $exchange->save();

        // Load relationships for notification
        $exchange->load(['initiateur', 'recepteur', 'bookDemande']);

        // Notify about status change
        $notificationService = new NotificationService();
        $notificationService->notifyExchangeStatusChange($exchange, $oldStatus, $exchange->status);

        // Check if this is an AJAX request or form request
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Échange mis à jour avec succès.', 'exchange' => $exchange]);
        }

        // For form submissions, redirect back with success message
        return redirect()->route('user.dashboard')->with('success', 'Échange confirmé avec succès.');
    }

    // Suivre l’historique
    public function exchangeHistory(Request $request)
    {
        $status = $request->query('status');

        $exchanges = Exchange::where('userInitiateurId', Auth::id())
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        return response()->json(['exchanges' => $exchanges]);
    }

    // Display the user dashboard with pending exchanges
    public function userDashboard()
    {
        $pendingExchanges = Exchange::where('userInitiateurId', Auth::id())
            ->where('status', 'EN_ATTENTE')
            ->get();

        return view('pages.user-dashboard', compact('pendingExchanges'));
    }

    // Display the form for creating a new exchange
    public function create()
    {
        $books = \App\Models\Book::with('user')->where('user_id', '!=', Auth::id())->get();
        $userBooks = \App\Models\Book::where('user_id', Auth::id())->get();
        return view('exchanges.create', compact('books', 'userBooks'));
    }

    // Store a new exchange
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'type' => 'required|string|in:RESERVATION,PRET,ECHANGE',
            'status' => 'required|string',
            'dateDebut' => 'required|date|after_or_equal:today',
            'dateFin' => 'required|date|after:dateDebut',
            'bookDemandeId' => 'required|exists:books,id',
        ];

        // Custom validation messages
        $messages = [
            'type.required' => 'Le type d\'échange est obligatoire.',
            'type.in' => 'Le type d\'échange doit être : Réservation, Prêt ou Échange.',
            'dateDebut.required' => 'La date de début est obligatoire.',
            'dateDebut.date' => 'La date de début doit être une date valide.',
            'dateDebut.after_or_equal' => 'La date de début ne peut pas être antérieure à aujourd\'hui.',
            'dateFin.required' => 'La date de fin est obligatoire.',
            'dateFin.date' => 'La date de fin doit être une date valide.',
            'dateFin.after' => 'La date de fin doit être postérieure à la date de début.',
            'bookDemandeId.required' => 'Vous devez sélectionner un livre.',
            'bookDemandeId.exists' => 'Le livre sélectionné n\'existe pas.',
            'bookOffertId.required' => 'Vous devez sélectionner un livre à offrir pour un échange.',
            'bookOffertId.exists' => 'Le livre offert n\'existe pas.',
            'bookOffertId.different' => 'Le livre offert doit être différent du livre demandé.',
        ];

        // Add bookOffertId validation only for ECHANGE type
        if ($request->input('type') === 'ECHANGE') {
            $rules['bookOffertId'] = 'required|exists:books,id|different:bookDemandeId';
        }

        $validated = $request->validate($rules, $messages);

        // Custom business logic validations
        $businessValidation = $this->validateExchangeBusinessRules($request);
        if ($businessValidation !== true) {
            return $businessValidation; // Return the redirect response with errors
        }

        // Default status to EN_ATTENTE if not set
        if (empty($validated['status'])) {
            $validated['status'] = 'EN_ATTENTE';
        }

        // Get the book to determine the receiver
        $book = \App\Models\Book::findOrFail($validated['bookDemandeId']);
        
        $exchangeData = [
            'type' => $validated['type'],
            'status' => $validated['status'],
            'dateDebut' => $validated['dateDebut'],
            'dateFin' => $validated['dateFin'],
            'userInitiateurId' => Auth::id(),
            'userRecepteurId' => $book->user_id,
            'bookDemandeId' => $validated['bookDemandeId'],
        ];

        // Add bookOffertId only if it's an exchange and provided
        if ($validated['type'] === 'ECHANGE' && isset($validated['bookOffertId'])) {
            $exchangeData['bookOffertId'] = $validated['bookOffertId'];
        }
        
        $exchange = Exchange::create($exchangeData);

        // Load relationships for notification
        $exchange->load(['initiateur', 'recepteur', 'bookDemande']);

        // Notify the book owner about the new exchange request
        $notificationService = new NotificationService();
        $notificationService->notifyBookOwnerOfExchangeRequest($exchange);

        return redirect()->route('exchanges.index')->with('success', 'Demande d\'échange envoyée avec succès! Le propriétaire du livre sera notifié.');
    }

    // Show a specific exchange
    public function show(Exchange $exchange)
    {
        // Ensure the user can view the exchange (either as initiator or receiver)
        if ($exchange->userInitiateurId !== Auth::id() && $exchange->userRecepteurId !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cet échange.');
        }

        $exchange->load(['initiateur', 'recepteur', 'bookDemande.user']);
        return view('exchanges.show', compact('exchange'));
    }

    // Show the form for editing an exchange
    public function edit(Exchange $exchange)
    {
        // Ensure the user can edit the exchange (either as initiator or receiver)
        if ($exchange->userInitiateurId !== Auth::id() && $exchange->userRecepteurId !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet échange.');
        }

        return view('exchanges.edit', compact('exchange'));
    }

    // Update an exchange
    public function update(Request $request, Exchange $exchange)
    {
        // Ensure the user can update the exchange (either as initiator or receiver)
        if ($exchange->userInitiateurId !== Auth::id() && $exchange->userRecepteurId !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet échange.');
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'status' => 'required|string',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date',
        ]);

        $oldStatus = $exchange->status;
        $exchange->update($validated);

        // If status changed, notify the other party
        if ($oldStatus !== $exchange->status) {
            $exchange->load(['initiateur', 'recepteur', 'bookDemande']);
            $notificationService = new NotificationService();
            $notificationService->notifyExchangeStatusChange($exchange, $oldStatus, $exchange->status);
        }

        return redirect()->route('exchanges.index')->with('success', 'Échange mis à jour avec succès.');
    }

    // Accept an exchange request (for book owners)
    public function accept(Exchange $exchange)
    {
        // Only the receiver (book owner) can accept
        if ($exchange->userRecepteurId !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à accepter cet échange.');
        }

        if ($exchange->status !== 'EN_ATTENTE') {
            return redirect()->back()->with('error', 'Cet échange ne peut plus être accepté.');
        }

        $oldStatus = $exchange->status;
        $exchange->update(['status' => 'EN_COURS']);

        // Notify the initiator
        $exchange->load(['initiateur', 'recepteur', 'bookDemande']);
        $notificationService = new NotificationService();
        $notificationService->notifyExchangeStatusChange($exchange, $oldStatus, 'EN_COURS');

        return redirect()->back()->with('success', 'Échange accepté avec succès !');
    }

    /**
     * Validate business rules for exchange creation
     */
    private function validateExchangeBusinessRules(Request $request)
    {
        $bookDemandeId = $request->input('bookDemandeId');
        $bookOffertId = $request->input('bookOffertId');
        $type = $request->input('type');
        
        // Check if user is trying to create exchange with their own book
        if ($bookDemandeId) {
            $book = \App\Models\Book::find($bookDemandeId);
            if ($book && $book->user_id == Auth::id()) {
                return redirect()->back()
                    ->withErrors(['bookDemandeId' => 'Vous ne pouvez pas créer un échange avec votre propre livre.'])
                    ->withInput();
            }
        }
        
        // For exchanges, validate that offered book belongs to current user
        if ($type === 'ECHANGE' && $bookOffertId) {
            $offeredBook = \App\Models\Book::find($bookOffertId);
            if ($offeredBook && $offeredBook->user_id != Auth::id()) {
                return redirect()->back()
                    ->withErrors(['bookOffertId' => 'Vous ne pouvez offrir que vos propres livres.'])
                    ->withInput();
            }
        }
        
        // Check for duplicate pending exchanges
        $existingExchange = Exchange::where('userInitiateurId', Auth::id())
            ->where('bookDemandeId', $bookDemandeId)
            ->where('status', 'EN_ATTENTE')
            ->first();
            
        if ($existingExchange) {
            return redirect()->back()
                ->withErrors(['bookDemandeId' => 'Vous avez déjà une demande d\'échange en attente pour ce livre.'])
                ->withInput();
        }
        
        // Validate date range (max 6 months)
        $dateDebut = \Carbon\Carbon::parse($request->input('dateDebut'));
        $dateFin = \Carbon\Carbon::parse($request->input('dateFin'));
        
        if ($dateFin->diffInMonths($dateDebut) > 6) {
            return redirect()->back()
                ->withErrors(['dateFin' => 'La durée de l\'échange ne peut pas dépasser 6 mois.'])
                ->withInput();
        }
        
        // All validations passed
        return true;
    }

    // Reject an exchange request (for book owners)
    public function reject(Exchange $exchange)
    {
        // Only the receiver (book owner) can reject
        if ($exchange->userRecepteurId !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à refuser cet échange.');
        }

        if ($exchange->status !== 'EN_ATTENTE') {
            return redirect()->back()->with('error', 'Cet échange ne peut plus être refusé.');
        }

        $oldStatus = $exchange->status;
        $exchange->update(['status' => 'ANNULE']);

        // Notify the initiator
        $exchange->load(['initiateur', 'recepteur', 'bookDemande']);
        $notificationService = new NotificationService();
        $notificationService->notifyExchangeStatusChange($exchange, $oldStatus, 'ANNULE');

        return redirect()->back()->with('success', 'Échange refusé.');
    }
}