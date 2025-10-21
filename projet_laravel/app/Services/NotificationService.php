<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a new exchange request notification for the book owner
     */
    public function notifyBookOwnerOfExchangeRequest($exchange)
    {
        $bookOwner = $exchange->recepteur; // The book owner
        $initiator = $exchange->initiateur; // The person requesting the exchange
        $book = $exchange->bookDemande;

        if (!$bookOwner) {
            return;
        }

        return Notification::create([
            'user_id' => $bookOwner->id,
            'type' => 'exchange_request',
            'title' => 'Nouvelle demande d\'échange',
            'message' => "{$initiator->name} souhaite échanger votre livre \"{$book->title}\". Consultez les détails et acceptez ou refusez cette demande.",
            'data' => [
                'exchange_id' => $exchange->id,
                'book_id' => $book->id,
                'initiator_id' => $initiator->id,
                'initiator_name' => $initiator->name,
                'book_title' => $book->title,
                'exchange_type' => $exchange->type,
                'requested_date' => $exchange->dateDebut,
            ],
        ]);
    }

    /**
     * Create a notification when exchange status changes
     */
    public function notifyExchangeStatusChange($exchange, $oldStatus, $newStatus)
    {
        $recipient = $exchange->initiateur; // Notify the person who initiated the exchange
        $bookOwner = $exchange->recepteur;
        $book = $exchange->bookDemande;

        if (!$recipient) {
            return;
        }

        $statusMessages = [
            'EN_COURS' => "Votre demande d'échange pour \"{$book->title}\" a été acceptée par {$bookOwner->name}.",
            'TERMINE' => "L'échange du livre \"{$book->title}\" avec {$bookOwner->name} est maintenant terminé.",
            'ANNULE' => "Votre demande d'échange pour \"{$book->title}\" a été refusée par {$bookOwner->name}.",
        ];

        $statusTitles = [
            'EN_COURS' => 'Échange accepté',
            'TERMINE' => 'Échange terminé',
            'ANNULE' => 'Échange refusé',
        ];

        if (!isset($statusMessages[$newStatus])) {
            return;
        }

        return Notification::create([
            'user_id' => $recipient->id,
            'type' => 'exchange_status_change',
            'title' => $statusTitles[$newStatus],
            'message' => $statusMessages[$newStatus],
            'data' => [
                'exchange_id' => $exchange->id,
                'book_id' => $book->id,
                'book_owner_id' => $bookOwner->id,
                'book_owner_name' => $bookOwner->name,
                'book_title' => $book->title,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * Get recent notifications for a user
     */
    public function getRecentNotifications($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Notify all admins when a new report is created
     */
    public function notifyAdminsOfNewReport($report)
    {
        $admins = User::where('role', 'admin')->get();
        $reporter = $report->reporter;
        $reportType = $report->type === 'CONFLIT_ECHANGE' ? 'conflit d\'échange' : 'comportement inapproprié';

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'new_report',
                'title' => 'Nouveau signalement',
                'message' => "Un nouveau signalement de {$reportType} a été créé par {$reporter->name}. Veuillez examiner ce signalement.",
                'data' => [
                    'report_id' => $report->id,
                    'reporter_id' => $reporter->id,
                    'reporter_name' => $reporter->name,
                    'report_type' => $report->type,
                    'reported_user_id' => $report->reported_user_id,
                    'exchange_id' => $report->exchange_id,
                ],
            ]);
        }
    }

    /**
     * Notify the reporter when report status changes
     */
    public function notifyReportStatusChange($report, $oldStatus, $newStatus)
    {
        $reporter = $report->reporter;
        
        if (!$reporter) {
            return;
        }

        $statusMessages = [
            'TRAITE' => "Votre signalement a été examiné et traité par les administrateurs.",
            'REJETE' => "Votre signalement a été examiné et rejeté par les administrateurs.",
        ];

        $statusTitles = [
            'TRAITE' => 'Signalement traité',
            'REJETE' => 'Signalement rejeté',
        ];

        if (!isset($statusMessages[$newStatus])) {
            return;
        }

        return Notification::create([
            'user_id' => $reporter->id,
            'type' => 'report_status_change',
            'title' => $statusTitles[$newStatus],
            'message' => $statusMessages[$newStatus],
            'data' => [
                'report_id' => $report->id,
                'report_type' => $report->type,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reported_user_id' => $report->reported_user_id,
                'exchange_id' => $report->exchange_id,
            ],
        ]);
    }

    /**
     * Notifier le propriétaire d'une nouvelle demande de location
     */
    public function notifyOwnerOfLocationRequest($location)
    {
        $proprietaire = $location->proprietaire;
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$proprietaire) {
            return;
        }

        return Notification::create([
            'user_id' => $proprietaire->id,
            'type' => 'location_request',
            'title' => 'Nouvelle demande de location',
            'message' => "{$locataire->name} souhaite louer votre livre \"{$book->title}\" pour {$location->duree_jours} jours à {$location->prix}€.",
            'data' => [
                'location_id' => $location->id,
                'book_id' => $book->id,
                'locataire_id' => $locataire->id,
                'locataire_name' => $locataire->name,
                'book_title' => $book->title,
                'date_location' => $location->date_location->format('Y-m-d'),
                'duree_jours' => $location->duree_jours,
                'prix' => $location->prix,
                'localisation' => $location->localisation,
            ],
        ]);
    }

    /**
     * Notifier le locataire que sa demande a été acceptée et qu'il doit payer
     */
    public function notifyTenantLocationAccepted($location, $payment)
    {
        $locataire = $location->locataire;
        $proprietaire = $location->proprietaire;
        $book = $location->book;

        if (!$locataire) {
            return;
        }

        return Notification::create([
            'user_id' => $locataire->id,
            'type' => 'location_accepted_payment_required',
            'title' => 'Location acceptée - Paiement requis',
            'message' => "{$proprietaire->name} a accepté votre demande de location pour \"{$book->title}\". Veuillez effectuer le paiement de {$payment->montant}€ pour confirmer la réservation.",
            'data' => [
                'location_id' => $location->id,
                'payment_id' => $payment->id,
                'book_id' => $book->id,
                'proprietaire_id' => $proprietaire->id,
                'proprietaire_name' => $proprietaire->name,
                'book_title' => $book->title,
                'montant' => $payment->montant,
            ],
        ]);
    }

    /**
     * Notifier le locataire que sa demande a été refusée
     */
    public function notifyTenantLocationRejected($location)
    {
        $locataire = $location->locataire;
        $proprietaire = $location->proprietaire;
        $book = $location->book;

        if (!$locataire) {
            return;
        }

        return Notification::create([
            'user_id' => $locataire->id,
            'type' => 'location_rejected',
            'title' => 'Demande de location refusée',
            'message' => "{$proprietaire->name} a refusé votre demande de location pour \"{$book->title}\".",
            'data' => [
                'location_id' => $location->id,
                'book_id' => $book->id,
                'proprietaire_id' => $proprietaire->id,
                'proprietaire_name' => $proprietaire->name,
                'book_title' => $book->title,
            ],
        ]);
    }

    /**
     * Notifier le propriétaire qu'un paiement a été effectué
     */
    public function notifyOwnerPaymentReceived($location, $payment)
    {
        $proprietaire = $location->proprietaire;
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$proprietaire) {
            return;
        }

        return Notification::create([
            'user_id' => $proprietaire->id,
            'type' => 'payment_received',
            'title' => 'Paiement reçu',
            'message' => "{$locataire->name} a effectué le paiement de {$payment->montant}€ pour la location de \"{$book->title}\". Vous pouvez maintenant démarrer la location.",
            'data' => [
                'location_id' => $location->id,
                'payment_id' => $payment->id,
                'book_id' => $book->id,
                'locataire_id' => $locataire->id,
                'locataire_name' => $locataire->name,
                'book_title' => $book->title,
                'montant' => $payment->montant,
                'methode_paiement' => $payment->methode_paiement,
            ],
        ]);
    }

    /**
     * Notifier le locataire que son paiement a été confirmé
     */
    public function notifyTenantPaymentConfirmed($location, $payment)
    {
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$locataire) {
            return;
        }

        return Notification::create([
            'user_id' => $locataire->id,
            'type' => 'payment_confirmed',
            'title' => 'Paiement confirmé',
            'message' => "Votre paiement de {$payment->montant}€ pour la location de \"{$book->title}\" a été confirmé. Référence: {$payment->reference_transaction}",
            'data' => [
                'location_id' => $location->id,
                'payment_id' => $payment->id,
                'book_id' => $book->id,
                'book_title' => $book->title,
                'montant' => $payment->montant,
                'reference' => $payment->reference_transaction,
            ],
        ]);
    }

    /**
     * Notifier le locataire que la location a démarré
     */
    public function notifyTenantLocationStarted($location)
    {
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$locataire) {
            return;
        }

        return Notification::create([
            'user_id' => $locataire->id,
            'type' => 'location_started',
            'title' => 'Location démarrée',
            'message' => "La location de \"{$book->title}\" a démarré. N'oubliez pas de retourner le livre avant le {$location->date_fin_prevue->format('d/m/Y')}.",
            'data' => [
                'location_id' => $location->id,
                'book_id' => $book->id,
                'book_title' => $book->title,
                'date_fin_prevue' => $location->date_fin_prevue->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Notifier le propriétaire que la location a démarré
     */
    public function notifyOwnerLocationStarted($location)
    {
        $proprietaire = $location->proprietaire;
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$proprietaire) {
            return;
        }

        return Notification::create([
            'user_id' => $proprietaire->id,
            'type' => 'location_started_owner',
            'title' => 'Location démarrée',
            'message' => "La location de \"{$book->title}\" à {$locataire->name} a démarré. Retour prévu le {$location->date_fin_prevue->format('d/m/Y')}.",
            'data' => [
                'location_id' => $location->id,
                'book_id' => $book->id,
                'locataire_id' => $locataire->id,
                'locataire_name' => $locataire->name,
                'book_title' => $book->title,
                'date_fin_prevue' => $location->date_fin_prevue->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Notifier le locataire que la location est terminée
     */
    public function notifyTenantLocationCompleted($location)
    {
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$locataire) {
            return;
        }

        return Notification::create([
            'user_id' => $locataire->id,
            'type' => 'location_completed',
            'title' => 'Location terminée',
            'message' => "Merci d'avoir retourné \"{$book->title}\". Nous espérons que vous avez apprécié votre lecture !",
            'data' => [
                'location_id' => $location->id,
                'book_id' => $book->id,
                'book_title' => $book->title,
            ],
        ]);
    }

    /**
     * Notifier le propriétaire que la location est terminée
     */
    public function notifyOwnerLocationCompleted($location)
    {
        $proprietaire = $location->proprietaire;
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$proprietaire) {
            return;
        }

        return Notification::create([
            'user_id' => $proprietaire->id,
            'type' => 'location_completed_owner',
            'title' => 'Location terminée',
            'message' => "{$locataire->name} a retourné \"{$book->title}\". La location est maintenant terminée.",
            'data' => [
                'location_id' => $location->id,
                'book_id' => $book->id,
                'locataire_id' => $locataire->id,
                'locataire_name' => $locataire->name,
                'book_title' => $book->title,
            ],
        ]);
    }

    /**
     * Notifier le propriétaire qu'un paiement a été annulé
     */
    public function notifyOwnerPaymentCancelled($location, $payment)
    {
        $proprietaire = $location->proprietaire;
        $locataire = $location->locataire;
        $book = $location->book;

        if (!$proprietaire) {
            return;
        }

        return Notification::create([
            'user_id' => $proprietaire->id,
            'type' => 'payment_cancelled',
            'title' => 'Paiement annulé',
            'message' => "{$locataire->name} a annulé le paiement pour la location de \"{$book->title}\". La location a été annulée.",
            'data' => [
                'location_id' => $location->id,
                'payment_id' => $payment->id,
                'book_id' => $book->id,
                'locataire_id' => $locataire->id,
                'locataire_name' => $locataire->name,
                'book_title' => $book->title,
            ],
        ]);
    }
}