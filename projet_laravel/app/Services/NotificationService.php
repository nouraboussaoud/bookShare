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
}