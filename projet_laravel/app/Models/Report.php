<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'priority_score',
        'priority_level',
        'emotion_type',
        'emotion_score',
        'moderator_id',
        'reviewed_at',
        'resolved_at',
        'admin_notes',
        'action_taken',
        'similar_reports_count',
        'is_recurring_offender',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
        'priority_score' => 'integer',
        'emotion_score' => 'decimal:2',
        'similar_reports_count' => 'integer',
        'is_recurring_offender' => 'boolean',
        'reviewed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constants for types and statuses
    const TYPE_CONFLIT_ECHANGE = 'CONFLIT_ECHANGE';
    const TYPE_COMPORTEMENT = 'COMPORTEMENT';

    const STATUS_EN_ATTENTE = 'EN_ATTENTE';
    const STATUS_TRAITE = 'TRAITE';
    const STATUS_REJETE = 'REJETE';

    // Constants for priority levels
    const PRIORITY_NORMALE = 'normale';
    const PRIORITY_MOYENNE = 'moyenne';
    const PRIORITY_HAUTE = 'haute';
    const PRIORITY_CRITIQUE = 'critique';

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

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
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

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority_level', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority_level', [self::PRIORITY_HAUTE, self::PRIORITY_CRITIQUE]);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority_level', self::PRIORITY_CRITIQUE);
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

    public function isHighPriority()
    {
        return in_array($this->priority_level, [self::PRIORITY_HAUTE, self::PRIORITY_CRITIQUE]);
    }

    public function isUrgent()
    {
        return $this->priority_level === self::PRIORITY_CRITIQUE;
    }

    /**
     * Calculer automatiquement le score de priorité
     */
    public function calculatePriorityScore()
    {
        $score = 0;

        // Critère 1: Gravité du type (40%)
        if ($this->type === self::TYPE_COMPORTEMENT) {
            $score += 4; // Comportement inapproprié est plus grave
        } else {
            $score += 2; // Conflit d'échange
        }

        // Critère 2: Nombre de signalements similaires (30%)
        $similarCount = $this->similar_reports_count ?? 0;
        if ($similarCount >= 5) {
            $score += 3;
        } elseif ($similarCount >= 3) {
            $score += 2;
        } elseif ($similarCount >= 1) {
            $score += 1;
        }

        // Critère 3: Utilisateur récidiviste (20%)
        if ($this->is_recurring_offender) {
            $score += 2;
        }

        // Critère 4: Ancienneté du signalement (10%)
        if ($this->created_at) {
            $hoursOld = $this->created_at->diffInHours(now());
            if ($hoursOld >= 72) { // Plus de 3 jours
                $score += 1;
            }
        }

        // Bonus: Émotion forte détectée
        if ($this->emotion_score && $this->emotion_score >= 80) {
            $score += 1;
        }

        return min($score, 10); // Score max = 10
    }

    /**
     * Mettre à jour automatiquement le niveau de priorité basé sur le score
     */
    public function updatePriorityLevel()
    {
        $score = $this->calculatePriorityScore();
        
        $this->priority_score = $score;
        
        if ($score >= 8) {
            $this->priority_level = self::PRIORITY_CRITIQUE;
        } elseif ($score >= 6) {
            $this->priority_level = self::PRIORITY_HAUTE;
        } elseif ($score >= 4) {
            $this->priority_level = self::PRIORITY_MOYENNE;
        } else {
            $this->priority_level = self::PRIORITY_NORMALE;
        }

        $this->save();
    }

    /**
     * Compter les signalements similaires
     */
    public function countSimilarReports()
    {
        if (!$this->reported_user_id) {
            return 0;
        }

        return self::where('reported_user_id', $this->reported_user_id)
            ->where('id', '!=', $this->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
    }

    /**
     * Vérifier si l'utilisateur signalé est récidiviste
     */
    public function checkIfRecurringOffender()
    {
        if (!$this->reported_user_id) {
            return false;
        }

        $resolvedReports = self::where('reported_user_id', $this->reported_user_id)
            ->where('status', self::STATUS_TRAITE)
            ->where('created_at', '>=', now()->subMonths(6))
            ->count();

        return $resolvedReports >= 3;
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

    // Get available priority levels
    public static function getPriorityLevels()
    {
        return [
            self::PRIORITY_NORMALE => 'Normale',
            self::PRIORITY_MOYENNE => 'Moyenne',
            self::PRIORITY_HAUTE => 'Haute',
            self::PRIORITY_CRITIQUE => 'Critique',
        ];
    }

    // Priority badge color helper
    public function getPriorityColorAttribute()
    {
        return match($this->priority_level) {
            self::PRIORITY_CRITIQUE => 'danger',
            self::PRIORITY_HAUTE => 'warning',
            self::PRIORITY_MOYENNE => 'info',
            self::PRIORITY_NORMALE => 'secondary',
            default => 'secondary',
        };
    }

    // Priority icon helper
    public function getPriorityIconAttribute()
    {
        return match($this->priority_level) {
            self::PRIORITY_CRITIQUE => '🔴',
            self::PRIORITY_HAUTE => '🟠',
            self::PRIORITY_MOYENNE => '🟡',
            self::PRIORITY_NORMALE => '🟢',
            default => '⚪',
        };
    }

    /**
     * Boot method pour les événements
     */
    protected static function booted()
    {
        // Avant création, calculer la priorité
        static::creating(function ($report) {
            $report->similar_reports_count = $report->countSimilarReports();
            $report->is_recurring_offender = $report->checkIfRecurringOffender();
            $report->priority_score = $report->calculatePriorityScore();
            
            // Définir le niveau de priorité
            $score = $report->priority_score;
            if ($score >= 8) {
                $report->priority_level = self::PRIORITY_CRITIQUE;
            } elseif ($score >= 6) {
                $report->priority_level = self::PRIORITY_HAUTE;
            } elseif ($score >= 4) {
                $report->priority_level = self::PRIORITY_MOYENNE;
            } else {
                $report->priority_level = self::PRIORITY_NORMALE;
            }
        });
    }
}