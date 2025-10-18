<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'status',
        'category_id',
        'recommended_age',
        'photo',
        'description',
    ];

    // Relations
    public function exchangesDemande()
    {
        return $this->hasMany(Exchange::class, 'bookDemandeId');
    }

    public function exchangesOffert()
    {
        return $this->hasMany(Exchange::class, 'bookOffertId');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }

    /**
     * Calcule la note moyenne des avis
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Retourne le nombre total d'avis
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Retourne tous les avis approuvés
     */
    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function getAgeDisplayAttribute()
    {
        return $this->recommended_age == 0 ? 'Tout âge' : $this->recommended_age . '+';
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAge($query, $age)
    {
        return $query->where('recommended_age', '<=', $age);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-book-cover.svg');
    }

    public function hasPhoto()
    {
        return !empty($this->photo) && file_exists(storage_path('app/public/' . $this->photo));
    }

    /**
     * Vérifie si le livre est disponible pour la location
     */
    public function estDisponiblePourLocation(): bool
    {
        // Le livre doit être disponible et ne pas avoir de location active
        return $this->status === 'available' && !$this->estEnLocation();
    }

    /**
     * Vérifie si le livre est actuellement en location
     */
    public function estEnLocation(): bool
    {
        return $this->locations()
            ->whereIn('statut', ['confirmee', 'en_cours'])
            ->exists();
    }

    /**
     * Retourne la location active s'il y en a une
     */
    public function getLocationActive()
    {
        return $this->locations()
            ->whereIn('statut', ['confirmee', 'en_cours'])
            ->with(['locataire', 'proprietaire'])
            ->first();
    }
}
