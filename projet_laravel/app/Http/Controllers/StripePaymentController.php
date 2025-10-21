<?php

namespace App\Http\Controllers;

use App\Models\ReservationPayment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;

class StripePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Créer une session de paiement Stripe Checkout
     */
    public function createCheckoutSession(ReservationPayment $payment)
    {
        // Vérifier que l'utilisateur est le locataire
        if ($payment->location->locataire_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à effectuer ce paiement.');
        }

        // Vérifier que le paiement est en attente
        if ($payment->statut_paiement !== 'en_attente') {
            return redirect()->route('payments.index')
                ->with('error', 'Ce paiement a déjà été traité.');
        }

        // Vérifier que les clés Stripe sont configurées
        if (!config('stripe.secret') || !config('stripe.key')) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Les clés Stripe ne sont pas configurées. Veuillez contacter l\'administrateur ou utiliser une autre méthode de paiement.');
        }

        try {
            // Créer une session Stripe Checkout
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Location du livre: ' . $payment->location->book->title,
                            'description' => 'Durée: ' . $payment->location->duree_jours . ' jours | Propriétaire: ' . $payment->location->proprietaire->name,
                            'images' => [$payment->location->book->image_url ?? 'https://via.placeholder.com/300x400?text=Livre'],
                        ],
                        'unit_amount' => intval($payment->montant * 100), // Stripe utilise les centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success', ['payment' => $payment->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel', ['payment' => $payment->id]),
                'client_reference_id' => $payment->id,
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'location_id' => $payment->location_id,
                    'user_id' => Auth::id(),
                ],
            ]);

            // Rediriger vers la page de paiement Stripe
            return redirect($session->url);
            
        } catch (\Exception $e) {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Erreur lors de la création de la session Stripe: ' . $e->getMessage());
        }
    }

    /**
     * Gérer le succès du paiement Stripe
     */
    public function success(Request $request, ReservationPayment $payment)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('payments.index')
                ->with('error', 'Session Stripe invalide.');
        }

        try {
            // Récupérer la session Stripe pour vérifier le paiement
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid' && $payment->statut_paiement === 'en_attente') {
                // Marquer le paiement comme complété
                $payment->statut_paiement = 'complete';
                $payment->methode_paiement = 'stripe';
                $payment->reference_transaction = $session->payment_intent;
                $payment->date_paiement = Carbon::now();
                $payment->save();

                $location = $payment->location;

                // Notifier le propriétaire que le paiement a été reçu
                Notification::create([
                    'user_id' => $location->proprietaire_id,
                    'type' => 'payment_received',
                    'title' => 'Paiement reçu via Stripe',
                    'message' => 'Le paiement de ' . number_format($payment->montant, 2) . '€ a été reçu pour la location du livre "' . $location->book->title . '".',
                    'related_id' => $location->id,
                    'related_type' => 'location',
                    'is_read' => false
                ]);

                // Notifier le locataire de la confirmation du paiement
                Notification::create([
                    'user_id' => $location->locataire_id,
                    'type' => 'payment_confirmed',
                    'title' => 'Paiement Stripe confirmé',
                    'message' => 'Votre paiement de ' . number_format($payment->montant, 2) . '€ a été confirmé par Stripe. Le propriétaire va démarrer la location.',
                    'related_id' => $location->id,
                    'related_type' => 'location',
                    'is_read' => false
                ]);

                return redirect()->route('payments.index')
                    ->with('success', 'Paiement effectué avec succès via Stripe! Le propriétaire a été notifié.');
            }

            return redirect()->route('payments.index')
                ->with('info', 'Le paiement est en cours de traitement.');
                
        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', 'Erreur lors de la vérification du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Gérer l'annulation du paiement Stripe
     */
    public function cancel(ReservationPayment $payment)
    {
        return redirect()->route('payments.show', $payment)
            ->with('info', 'Le paiement Stripe a été annulé. Vous pouvez réessayer.');
    }

    /**
     * Webhook Stripe pour gérer les événements de paiement
     */
    public function webhook(Request $request)
    {
        $endpoint_secret = config('stripe.webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Gérer les différents types d'événements
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $paymentId = $session->metadata->payment_id ?? null;

                if ($paymentId) {
                    $payment = ReservationPayment::find($paymentId);
                    if ($payment && $payment->statut_paiement === 'en_attente') {
                        $payment->statut_paiement = 'complete';
                        $payment->methode_paiement = 'stripe';
                        $payment->reference_transaction = $session->payment_intent;
                        $payment->date_paiement = Carbon::now();
                        $payment->save();

                        // Créer les notifications...
                    }
                }
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                // Gérer l'échec du paiement
                break;

            default:
                // Événement non géré
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
