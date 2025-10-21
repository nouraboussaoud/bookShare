<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'reading_group_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'max_attendees',
        'duration_minutes',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'max_attendees' => 'integer',
        'duration_minutes' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure events always have a valid reading group
        static::creating(function ($event) {
            if (!$event->reading_group_id || !ReadingGroup::find($event->reading_group_id)) {
                throw new \Exception('Cannot create event: Invalid or missing reading group');
            }
        });

        static::updating(function ($event) {
            if (!$event->reading_group_id || !ReadingGroup::find($event->reading_group_id)) {
                throw new \Exception('Cannot update event: Invalid or missing reading group');
            }
        });
    }

    /**
     * Get the reading group that owns this event
     */
    public function readingGroup()
    {
        return $this->belongsTo(ReadingGroup::class);
    }

    /**
     * Get the user who created this event
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all attendees for this event
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'group_event_attendees')
                    ->withPivot(['status', 'joined_at'])
                    ->withTimestamps();
    }

    /**
     * Get all chat messages for this event
     */
    public function chatMessages()
    {
        return $this->hasMany(EventChatMessage::class);
    }

    /**
     * Get approved chat messages
     */
    public function approvedChatMessages()
    {
        return $this->chatMessages()
                    ->where('moderation_status', 'approved')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get all polls for this event
     */
    public function polls()
    {
        return $this->hasMany(Poll::class, 'event_id');
    }

    /**
     * Get active polls for this event
     */
    public function activePolls()
    {
        return $this->polls()
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('closes_at')
                              ->orWhere('closes_at', '>', now());
                    });
    }

    /**
     * Get the count of confirmed attendees
     */
    public function confirmedAttendeesCount()
    {
        return $this->attendees()
                    ->wherePivot('status', 'confirmed')
                    ->count();
    }

    /**
     * Check if event is in the past
     */
    public function isPast()
    {
        return $this->event_date < now()->toDateString();
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming()
    {
        return $this->event_date >= now()->toDateString();
    }

    /**
     * Check if event is currently active (happening now)
     */
    public function isActive()
    {
        if (!$this->event_time) {
            return false;
        }

        $now = now();
        $eventDateTime = $this->event_date->setTimeFromTimeString($this->event_time->format('H:i:s'));
        
        // Use the specified duration or default to 120 minutes (2 hours)
        $durationMinutes = $this->duration_minutes ?? 120;
        $eventEndTime = $eventDateTime->copy()->addMinutes($durationMinutes);
        
        return $now->between($eventDateTime, $eventEndTime);
    }

    /**
     * Get event end time
     */
    public function getEventEndTime()
    {
        if (!$this->event_time) {
            return null;
        }

        $eventDateTime = $this->event_date->setTimeFromTimeString($this->event_time->format('H:i:s'));
        $durationMinutes = $this->duration_minutes ?? 120;
        
        return $eventDateTime->copy()->addMinutes($durationMinutes);
    }

    /**
     * Auto-close all active polls for this event
     */
    public function closeAllPolls()
    {
        $this->polls()
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }
}
