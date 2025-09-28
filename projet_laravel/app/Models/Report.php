<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'status',
        'reporter_id',
        'reported_user_id',
        'exchange_id',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
    ];

    // Constants for types and statuses
    const TYPE_CONFLIT_ECHANGE = 'CONFLIT_ECHANGE';
    const TYPE_COMPORTEMENT = 'COMPORTEMENT';

    const STATUS_EN_ATTENTE = 'EN_ATTENTE';
    const STATUS_TRAITE = 'TRAITE';
    const STATUS_REJETE = 'REJETE';

    // Relations
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_EN_ATTENTE);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', self::STATUS_TRAITE);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJETE);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_EN_ATTENTE;
    }

    public function isProcessed()
    {
        return $this->status === self::STATUS_TRAITE;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJETE;
    }

    // Get available types
    public static function getTypes()
    {
        return [
            self::TYPE_CONFLIT_ECHANGE => 'Conflit d\'échange',
            self::TYPE_COMPORTEMENT => 'Comportement inapproprié',
        ];
    }

    // Get available statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_EN_ATTENTE => 'En attente',
            self::STATUS_TRAITE => 'Traité',
            self::STATUS_REJETE => 'Rejeté',
        ];
    }
}