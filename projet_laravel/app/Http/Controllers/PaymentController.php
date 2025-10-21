<?php

namespace App\Http\Controllers;

use App\Models\ReservationPayment;
use App\Models\Location;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher les paiements en attente de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Paiements en attente (comme locataire)
        $paiementsEnAttente = ReservationPayment::with(['location.book', 'location.proprietaire'])
            ->whereHas('location', function($query) use ($user) {
                $query->where('locataire_id', $user->id);
            })
            ->where('statut_paiement', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Historique des paiements
        $historiquePaiements = ReservationPayment::with(['location.book', 'location.proprietaire'])
            ->whereHas('location', function($query) use ($user) {
                $query->where('locataire_id', $user->id);
            })
            ->whereIn('statut_paiement', ['complete', 'echoue', 'rembourse', 'annule'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('paiementsEnAttente', 'historiquePaiements'));
    }

    /**
     * Afficher le formulaire de paiement
     */
    public function show(ReservationPayment $payment)
    {
        // Vérifier que l'utilisateur est le locataire de cette location
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce paiement.');
        }

        return view('payments.show', compact('payment'));
    }

    /**
     * Traiter le paiement
     */
    public function process(Request $request, ReservationPayment $payment)
    {
        // Vérifier que l'utilisateur est le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à effectuer ce paiement.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->back()->with('error', 'Ce paiement a déjà été traité.');
        }

        $request->validate([
            'methode_paiement' => 'required|in:carte,virement,especes,paypal,stripe',
            'reference_transaction' => 'nullable|string|max:255'
        ]);

        // Simuler le traitement du paiement (dans un vrai système, on intégrerait PayPal, Stripe, etc.)
        $payment->statut_paiement = 'complete';
        $payment->methode_paiement = $request->methode_paiement;
        $payment->reference_transaction = $request->reference_transaction ?? 'REF-' . strtoupper(uniqid());
        $payment->date_paiement = Carbon::now();
        $payment->save();

        // Mettre à jour le statut de la location
        $location = $payment->location;
        // La location reste confirmée, elle sera démarrée manuellement par le propriétaire

        // Notifier le propriétaire que le paiement a été reçu
        Notification::create([
            'user_id' => $location->proprietaire_id,
            'type' => 'payment_received',
            'title' => 'Paiement reçu',
            'message' => 'Le paiement de ' . number_format($payment->montant, 2) . '€ a été reçu pour la location du livre "' . $location->book->title . '".',
            'related_id' => $location->id,
            'related_type' => 'location',
            'is_read' => false
        ]);

        // Notifier le locataire de la confirmation du paiement
        Notification::create([
            'user_id' => $location->locataire_id,
            'type' => 'payment_confirmed',
            'title' => 'Paiement confirmé',
            'message' => 'Votre paiement de ' . number_format($payment->montant, 2) . '€ a été confirmé. Le propriétaire va démarrer la location.',
            'related_id' => $location->id,
            'related_type' => 'location',
            'is_read' => false
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement effectué avec succès! Le propriétaire a été notifié.');
    }

    /**
     * Annuler un paiement
     */
    public function cancel(ReservationPayment $payment)
    {
        // Vérifier que l'utilisateur est le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler ce paiement.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->back()->with('error', 'Ce paiement ne peut plus être annulé.');
        }

        $payment->statut_paiement = 'annule';
        $payment->save();

        // Annuler aussi la location
        $location = $payment->location;
        $location->statut = 'annulee';
        $location->save();

        // Notifier le propriétaire de l'annulation
        Notification::create([
            'user_id' => $location->proprietaire_id,
            'type' => 'payment_cancelled',
            'title' => 'Paiement annulé',
            'message' => 'Le locataire a annulé le paiement pour la location du livre "' . $location->book->title . '".',
            'related_id' => $location->id,
            'related_type' => 'location',
            'is_read' => false
        ]);

        return redirect()->route('locations.index')
            ->with('info', 'Le paiement et la location ont été annulés.');
    }

    /**
     * Historique des paiements pour l'admin
     */
    public function adminIndex()
    {
        $payments = ReservationPayment::with(['location.book', 'location.locataire', 'location.proprietaire'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => ReservationPayment::count(),
            'en_attente' => ReservationPayment::where('statut_paiement', 'en_attente')->count(),
            'complete' => ReservationPayment::where('statut_paiement', 'complete')->count(),
            'echoue' => ReservationPayment::where('statut_paiement', 'echoue')->count(),
            'total_montant' => ReservationPayment::where('statut_paiement', 'complete')->sum('montant')
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }
}
