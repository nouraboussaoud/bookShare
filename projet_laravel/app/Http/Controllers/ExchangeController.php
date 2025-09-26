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
        $exchanges = Exchange::with(['initiateur', 'recepteur', 'bookDemande.owner'])
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
        $books = \App\Models\Book::with('owner')->where('owner_id', '!=', Auth::id())->get();
        $userBooks = \App\Models\Book::where('owner_id', Auth::id())->get();
        return view('exchanges.create', compact('books', 'userBooks'));
    }

    // Store a new exchange
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'type' => 'required|string|in:RESERVATION,PRET,ECHANGE',
            'status' => 'required|string',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after:dateDebut',
            'bookDemandeId' => 'required|exists:books,id',
        ];

        // Add bookOffertId validation only for ECHANGE type
        if ($request->input('type') === 'ECHANGE') {
            $rules['bookOffertId'] = 'required|exists:books,id';
        }

        $validated = $request->validate($rules);

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
            'userRecepteurId' => $book->owner_id,
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

        $exchange->load(['initiateur', 'recepteur', 'bookDemande.owner']);
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