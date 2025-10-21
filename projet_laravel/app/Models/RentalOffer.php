<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'prix_par_jour',
        'localisation',
        'duree_min_jours',
        'duree_max_jours',
        'conditions',
        'is_active'
    ];

    protected $casts = [
        'prix_par_jour' => 'decimal:2',
        'duree_min_jours' => 'integer',
        'duree_max_jours' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Relation avec le livre
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relation avec le propriétaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si l'offre est active
     */
    public function isActive(): bool
    {
        return $this->is_active && $this->book->status === 'available';
    }

    /**
     * Calculer le prix pour une durée donnée
     */
    public function calculatePrice(int $days): float
    {
        return $this->prix_par_jour * $days;
    }
}
