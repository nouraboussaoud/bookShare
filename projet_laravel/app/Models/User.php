<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is inactive
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    // Report relationships
    public function reportsCreated()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function reportsReceived()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }
    /**
     * Relations avec les locations
     */
    
    // Livres que l'utilisateur possède et loue à d'autres
    public function locationsCommeProprietaire(): HasMany
    {
        return $this->hasMany(Location::class, 'proprietaire_id');
    }

    // Livres que l'utilisateur loue à d'autres
    public function locationsCommeLocataire(): HasMany
    {
        return $this->hasMany(Location::class, 'locataire_id');
    }

    // Toutes les locations liées à l'utilisateur
    public function toutesLesLocations()
    {
        return Location::where('proprietaire_id', $this->id)
                      ->orWhere('locataire_id', $this->id);
    }

    // Livres possédés par l'utilisateur
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'user_id');
    }

    // Progression de lecture de l'utilisateur
    public function readingProgress(): HasMany
    {
        return $this->hasMany(ReadingProgress::class);
    }

    // Notifications personnalisées
    public function customNotifications(): HasMany
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    // Notifications non lues
    public function unreadCustomNotifications(): HasMany
    {
        return $this->hasMany(\App\Models\Notification::class)->where('is_read', false);
    }
}
