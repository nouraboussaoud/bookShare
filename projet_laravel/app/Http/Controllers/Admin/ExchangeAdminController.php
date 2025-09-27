<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;

class ExchangeAdminController extends Controller
{
    // Display all exchanges for admin management
    public function index()
    {
        $exchanges = Exchange::with(['initiateur', 'recepteur', 'bookDemande.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.exchanges.index', compact('exchanges'));
    }

    // Show a specific exchange for admin
    public function show(Exchange $exchange)
    {
        $exchange->load(['initiateur', 'recepteur', 'bookDemande.user']);
        return view('admin.exchanges.show', compact('exchange'));
    }

    // Show form to create new exchange (admin)
    public function create()
    {
        $users = User::all();
        $books = Book::with('user')->get();
        
        return view('admin.exchanges.create', compact('users', 'books'));
    }

    // Store new exchange (admin)
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'type' => 'required|string|in:RESERVATION,PRET,ECHANGE',
            'status' => 'required|string|in:EN_ATTENTE,EN_COURS,TERMINE,ANNULE',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after:dateDebut',
            'userInitiateurId' => 'required|exists:users,id',
            'userRecepteurId' => 'required|exists:users,id',
            'bookDemandeId' => 'required|exists:books,id',
        ];

        // Add bookOffertId validation only for ECHANGE type
        if ($request->input('type') === 'ECHANGE') {
            $rules['bookOffertId'] = 'required|exists:books,id';
        }

        $validated = $request->validate($rules);

        $exchangeData = [
            'type' => $validated['type'],
            'status' => $validated['status'],
            'dateDebut' => $validated['dateDebut'],
            'dateFin' => $validated['dateFin'],
            'userInitiateurId' => $validated['userInitiateurId'],
            'userRecepteurId' => $validated['userRecepteurId'],
            'bookDemandeId' => $validated['bookDemandeId'],
        ];

        // Add bookOffertId only if it's an exchange and provided
        if ($validated['type'] === 'ECHANGE' && isset($validated['bookOffertId'])) {
            $exchangeData['bookOffertId'] = $validated['bookOffertId'];
        }
        
        $exchange = Exchange::create($exchangeData);

        return redirect()->route('admin.exchanges.index')->with('success', 'Échange créé avec succès!');
    }

    // Show form to edit exchange (admin can edit any exchange)
    public function edit(Exchange $exchange)
    {
        $users = User::all();
        $books = Book::with('user')->get();
        $exchange->load(['initiateur', 'recepteur', 'bookDemande.user']);
        
        return view('admin.exchanges.edit', compact('exchange', 'users', 'books'));
    }

    // Update exchange (admin can update any exchange)
    public function update(Request $request, Exchange $exchange)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'status' => 'required|string',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date',
            'userInitiateurId' => 'required|exists:users,id',
            'userRecepteurId' => 'nullable|exists:users,id',
            'bookDemandeId' => 'nullable|exists:books,id',
        ]);

        $exchange->update($validated);

        return redirect()->route('admin.exchanges.index')
            ->with('success', 'Exchange updated successfully.');
    }

    // Delete an exchange (admin only)
    public function destroy(Exchange $exchange)
    {
        $exchange->delete();
        
        return redirect()->route('admin.exchanges.index')
            ->with('success', 'Exchange deleted successfully.');
    }

    // Supervise exchanges (change status to approved/rejected)
    public function supervise(Request $request, Exchange $exchange)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        if ($validated['action'] === 'approve') {
            $exchange->status = 'APPROUVE';
        } else {
            $exchange->status = 'REFUSE';
        }

        $exchange->save();

        return redirect()->back()
            ->with('success', 'Exchange status updated successfully.');
    }

    // Arbitrate exchanges (resolve conflicts)
    public function arbitrate(Request $request, Exchange $exchange)
    {
        // If request has specific status, use it (for detailed arbitration)
        if ($request->has('status')) {
            $validated = $request->validate([
                'status' => 'required|in:TERMINE,ANNULE',
                'admin_note' => 'nullable|string|max:500'
            ]);

            $exchange->status = $validated['status'];
            $exchange->admin_note = $validated['admin_note'] ?? null;
        } else {
            // Simple arbitration from dashboard button
            // Change status based on current status
            if ($exchange->status === 'EN_ATTENTE') {
                $exchange->status = 'EN_COURS';
                $message = 'Échange mis en cours par arbitrage administratif.';
            } elseif ($exchange->status === 'EN_COURS') {
                $exchange->status = 'TERMINE';
                $message = 'Échange terminé par arbitrage administratif.';
            } else {
                return redirect()->back()
                    ->with('error', 'Cet échange ne peut pas être arbitré dans son état actuel.');
            }
            
            $exchange->admin_note = $message;
        }

        $exchange->save();

        return redirect()->back()
            ->with('success', 'Échange arbitré avec succès.');
    }

    // Cancel an exchange (admin override)
    public function cancel(Exchange $exchange)
    {
        $exchange->status = 'ANNULE';
        $exchange->save();

        return redirect()->back()
            ->with('success', 'Exchange cancelled successfully.');
    }

    // Superviser les échanges (legacy API method)
    public function superviseExchanges(Request $request)
    {
        $status = $request->query('status');

        $exchanges = Exchange::with(['initiateur', 'recepteur', 'bookDemande.user'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })->get();

        return response()->json(['exchanges' => $exchanges]);
    }

    // Arbitrer un échange (legacy API method)
    public function arbitrateExchange(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:ANNULE,TERMINE',
        ]);

        $exchange = Exchange::findOrFail($id);
        $exchange->status = $validated['status'];
        $exchange->save();

        return response()->json(['message' => 'Statut mis à jour avec succès.', 'exchange' => $exchange]);
    }

    // Annuler un échange (legacy API method)
    public function cancelExchange($id)
    {
        $exchange = Exchange::findOrFail($id);
        $exchange->status = 'ANNULE';
        $exchange->save();

        return response()->json(['message' => 'Échange annulé avec succès.']);
    }
}