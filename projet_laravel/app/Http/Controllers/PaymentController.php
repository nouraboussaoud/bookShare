<?php

namespace App\Http\Controllers;

use App\Models\ReservationPayment;
use App\Models\Location;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Afficher la liste des paiements du locataire
     */
    public function index()
    {
        $paymentsEnAttente = ReservationPayment::whereHas('location', function($query) {
                $query->where('locataire_id', Auth::id());
            })
            ->where('statut_paiement', 'en_attente')
            ->with(['location.book', 'location.proprietaire'])
            ->latest()
            ->get();

        $paymentsHistorique = ReservationPayment::whereHas('location', function($query) {
                $query->where('locataire_id', Auth::id());
            })
            ->whereIn('statut_paiement', ['complete', 'echoue', 'annule', 'rembourse'])
            ->with(['location.book', 'location.proprietaire'])
            ->latest()
            ->paginate(15);

        return view('payments.index', compact('paymentsEnAttente', 'paymentsHistorique'));
    }

    /**
     * Afficher le formulaire de paiement
     */
    public function show(ReservationPayment $payment)
    {
        $payment->load(['location.book', 'location.proprietaire', 'location.locataire']);

        // Vérifier que c'est bien le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce paiement.');
        }

        // Vérifier si la location a déjà un paiement complété (empêcher double paiement)
        if ($payment->location->hasPaiementComplete()) {
            return redirect()->route('payments.index')
                ->with('info', 'Cette location a déjà été payée.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement n\'est plus en attente.');
        }

        return view('payments.show', compact('payment'));
    }

    /**
     * Traiter le paiement manuel
     */
    public function process(Request $request, ReservationPayment $payment)
    {
        // Vérifier que c'est bien le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce paiement.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement a déjà été traité.');
        }

        $request->validate([
            'methode_paiement' => 'required|in:carte,paypal,virement,especes,autre',
            'reference_transaction' => 'nullable|string|max:255',
        ]);

        // Mettre à jour le paiement
        $payment->update([
            'statut_paiement' => 'complete',
            'methode_paiement' => $request->methode_paiement,
            'reference_transaction' => $request->reference_transaction ?? 'REF-' . strtoupper(Str::random(10)),
            'date_paiement' => now(),
        ]);

        $location = $payment->location;

        // Envoyer les notifications
        $this->notificationService->notifyOwnerPaymentReceived($location, $payment);
        $this->notificationService->notifyTenantPaymentConfirmed($location, $payment);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement effectué avec succès! Le propriétaire a été notifié.');
    }

    /**
     * Annuler un paiement
     */
    public function cancel(Request $request, ReservationPayment $payment)
    {
        // Vérifier que c'est bien le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce paiement.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement ne peut plus être annulé.');
        }

        $location = $payment->location;

        // Mettre à jour le paiement et la location
        $payment->update([
            'statut_paiement' => 'annule',
        ]);

        $location->update([
            'statut' => 'annulee',
        ]);

        // Notifier le propriétaire
        $this->notificationService->notifyOwnerPaymentCancelled($location, $payment);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement annulé. La location a été annulée.');
    }

    /**
     * Créer une session Stripe Checkout
     */
    public function createStripeCheckout(ReservationPayment $payment)
    {
        // Vérifier que c'est bien le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce paiement.');
        }

        // Vérifier si la location a déjà un paiement complété (empêcher double paiement)
        if ($payment->location->hasPaiementComplete()) {
            return redirect()->route('payments.index')
                ->with('info', 'Cette location a déjà été payée. Impossible de payer à nouveau.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement a déjà été traité.');
        }

        $location = $payment->location;
        $book = $location->book;

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Location: ' . $book->title,
                            'description' => "Location de livre pour {$location->duree_jours} jours",
                        ],
                        'unit_amount' => (int)($payment->montant * 100), // Convertir en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payments.stripe.success', ['payment' => $payment->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.stripe.cancel', ['payment' => $payment->id]),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'location_id' => $location->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la session de paiement: ' . $e->getMessage());
        }
    }

    /**
     * Succès du paiement Stripe
     */
    public function stripeSuccess(Request $request, ReservationPayment $payment)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('payments.index')
                ->with('error', 'Session invalide.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Mettre à jour le paiement
                $payment->update([
                    'statut_paiement' => 'complete',
                    'methode_paiement' => 'stripe',
                    'reference_transaction' => $session->payment_intent,
                    'date_paiement' => now(),
                ]);

                $location = $payment->location;

                // Envoyer les notifications
                $this->notificationService->notifyOwnerPaymentReceived($location, $payment);
                $this->notificationService->notifyTenantPaymentConfirmed($location, $payment);

                return redirect()->route('payments.index')
                    ->with('success', 'Paiement effectué avec succès via Stripe! Le propriétaire a été notifié.');
            }
        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', 'Erreur lors de la vérification du paiement: ' . $e->getMessage());
        }

        return redirect()->route('payments.index')
            ->with('error', 'Le paiement n\'a pas été complété.');
    }

    /**
     * Annulation du paiement Stripe
     */
    public function stripeCancel(ReservationPayment $payment)
    {
        return redirect()->route('payments.show', $payment)
            ->with('info', 'Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.');
    }
}
