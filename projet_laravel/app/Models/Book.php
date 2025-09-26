<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'owner_id',
    ];

    // Relations
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function exchangesDemande()
    {
        return $this->hasMany(Exchange::class, 'bookDemandeId');
    }

    public function exchangesOffert()
    {
        return $this->hasMany(Exchange::class, 'bookOffertId');
    }
}