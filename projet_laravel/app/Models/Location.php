<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Location extends Model
{
    protected $fillable = [
        'book_id',
        'proprietaire_id',
        'locataire_id',
        'date_location',
        'duree_jours',
        'date_fin_prevue',
        'date_retour_effective',
        'localisation',
        'prix',
        'statut',
        'notes'
    ];

    protected $casts = [
        'date_location' => 'date',
        'date_fin_prevue' => 'date',
        'date_retour_effective' => 'date',
        'duree_jours' => 'integer',
        'prix' => 'decimal:2'
    ];

    // Relations
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function locataire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ReservationPayment::class);
    }

    // Méthodes utiles
    public function calculerDateFin()
    {
        // S'assurer que duree_jours est un entier
        $dureeJours = (int) $this->duree_jours;
        
        $this->date_fin_prevue = Carbon::parse($this->date_location)->addDays($dureeJours);
        return $this->date_fin_prevue;
    }

    public function estEnRetard(): bool
    {
        if ($this->statut === 'terminee' || $this->date_retour_effective) {
            return false;
        }
        
        return Carbon::now()->isAfter($this->date_fin_prevue);
    }

    public function joursDeRetard(): int
    {
        if (!$this->estEnRetard()) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->date_fin_prevue);
    }

    public function marquerCommeTerminee()
    {
        $this->date_retour_effective = Carbon::now();
        $this->statut = 'terminee';
        $this->save();
    }
}
