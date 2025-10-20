<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ReservationPayment extends Model
{
    protected $fillable = [
        'location_id',
        'montant',
        'type_paiement',
        'statut_paiement',
        'methode_paiement',
        'reference_transaction',
        'date_paiement',
        'date_remboursement',
        'notes'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
        'date_remboursement' => 'date'
    ];

    /**
     * Relation avec Location (Réservation)
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Vérifie si le paiement est complet
     */
    public function estComplete(): bool
    {
        return $this->statut_paiement === 'complete';
    }

    /**
     * Vérifie si le paiement est en attente
     */
    public function estEnAttente(): bool
    {
        return $this->statut_paiement === 'en_attente';
    }

    /**
     * Marquer le paiement comme complet
     */
    public function marquerCommeComplete()
    {
        $this->statut_paiement = 'complete';
        $this->date_paiement = Carbon::now();
        $this->save();
    }

    /**
     * Marquer le paiement comme échoué
     */
    public function marquerCommeEchoue()
    {
        $this->statut_paiement = 'echoue';
        $this->save();
    }

    /**
     * Rembourser le paiement
     */
    public function rembourser()
    {
        $this->statut_paiement = 'rembourse';
        $this->date_remboursement = Carbon::now();
        $this->save();
    }

    /**
     * Scope pour filtrer les paiements par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type_paiement', $type);
    }

    /**
     * Scope pour filtrer les paiements par statut
     */
    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut_paiement', $statut);
    }

    /**
     * Obtenir le badge de couleur pour le statut
     */
    public function getStatutBadgeClass(): string
    {
        return match($this->statut_paiement) {
            'complete' => 'badge-success',
            'en_attente' => 'badge-warning',
            'echoue' => 'badge-danger',
            'rembourse' => 'badge-info',
            'annule' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatutLabel(): string
    {
        return match($this->statut_paiement) {
            'complete' => 'Complété',
            'en_attente' => 'En attente',
            'echoue' => 'Échoué',
            'rembourse' => 'Remboursé',
            'annule' => 'Annulé',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir le label du type de paiement
     */
    public function getTypeLabel(): string
    {
        return match($this->type_paiement) {
            'caution' => 'Caution',
            'location' => 'Location',
            'penalite' => 'Pénalité',
            'remboursement' => 'Remboursement',
            default => 'Autre'
        };
    }
}
