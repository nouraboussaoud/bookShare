<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ReadingProgress extends Model
{
    use HasFactory;

    protected $table = 'reading_progress';

    protected $fillable = [
        'user_id',
        'book_id',
        'current_page',
        'total_pages',
        'status',
        'started_at',
        'finished_at',
        'reading_time_minutes',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'current_page' => 'integer',
        'total_pages' => 'integer',
        'reading_time_minutes' => 'integer',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Méthodes utiles

    /**
     * Calcule le pourcentage de progression
     */
    public function getProgressPercentageAttribute(): float
    {
        if (!$this->total_pages || $this->total_pages == 0) {
            return 0;
        }
        
        return min(100, round(($this->current_page / $this->total_pages) * 100, 2));
    }

    /**
     * Retourne le nombre de pages restantes
     */
    public function getPagesRemainingAttribute(): int
    {
        if (!$this->total_pages) {
            return 0;
        }
        
        return max(0, $this->total_pages - $this->current_page);
    }

    /**
     * Vérifie si la lecture est terminée
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifie si la lecture est en cours
     */
    public function isReading(): bool
    {
        return $this->status === 'reading';
    }

    /**
     * Marque la lecture comme commencée
     */
    public function startReading(): void
    {
        if (!$this->started_at) {
            $this->started_at = Carbon::now();
        }
        
        if ($this->status === 'to_read') {
            $this->status = 'reading';
        }
        
        $this->save();
    }

    /**
     * Marque la lecture comme terminée
     */
    public function completeReading(): void
    {
        $this->status = 'completed';
        $this->finished_at = Carbon::now();
        
        if ($this->total_pages) {
            $this->current_page = $this->total_pages;
        }
        
        $this->save();
    }

    /**
     * Marque la lecture comme abandonnée
     */
    public function abandonReading(): void
    {
        $this->status = 'abandoned';
        $this->save();
    }

    /**
     * Met à jour la page actuelle
     */
    public function updateProgress(int $currentPage): void
    {
        $this->current_page = $currentPage;
        
        // Démarre automatiquement la lecture si ce n'est pas fait
        if ($this->status === 'to_read' && $currentPage > 0) {
            $this->startReading();
        }
        
        // Marque comme terminé si on atteint la dernière page
        if ($this->total_pages && $currentPage >= $this->total_pages) {
            $this->completeReading();
        } else {
            $this->save();
        }
    }

    /**
     * Ajoute du temps de lecture
     */
    public function addReadingTime(int $minutes): void
    {
        $this->reading_time_minutes += $minutes;
        $this->save();
    }

    /**
     * Retourne le temps de lecture formaté
     */
    public function getFormattedReadingTimeAttribute(): string
    {
        $hours = floor($this->reading_time_minutes / 60);
        $minutes = $this->reading_time_minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}min";
        }
        
        return "{$minutes}min";
    }

    /**
     * Retourne le statut en français
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'to_read' => 'À lire',
            'reading' => 'En cours',
            'completed' => 'Terminé',
            'abandoned' => 'Abandonné',
            default => 'Inconnu',
        };
    }

    /**
     * Scopes
     */
    public function scopeReading($query)
    {
        return $query->where('status', 'reading');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToRead($query)
    {
        return $query->where('status', 'to_read');
    }

    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }
}
