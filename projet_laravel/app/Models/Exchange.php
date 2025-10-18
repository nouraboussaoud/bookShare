<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'dateDebut',
        'dateFin',
        'userInitiateurId',
        'userRecepteurId',
        'bookDemandeId',
        'bookOffertId',
        'admin_note',
    ];

    // Relations
    public function initiateur()
    {
        return $this->belongsTo(User::class, 'userInitiateurId');
    }

    public function recepteur()
    {
        return $this->belongsTo(User::class, 'userRecepteurId');
    }

    public function bookDemande()
    {
        return $this->belongsTo(Book::class, 'bookDemandeId');
    }

    public function bookOffert()
    {
        return $this->belongsTo(Book::class, 'bookOffertId');
    }
}