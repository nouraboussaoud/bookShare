<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TypingStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_event_id',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /**
     * Get the user that owns the typing status
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that owns the typing status
     */
    public function groupEvent()
    {
        return $this->belongsTo(GroupEvent::class, 'group_event_id');
    }

    /**
     * Check if the typing status is still active (within last 5 seconds)
     */
    public function isActive()
    {
        return $this->last_activity->diffInSeconds(Carbon::now()) < 5;
    }

    /**
     * Scope for active typing statuses
     */
    public function scopeActive($query)
    {
        return $query->where('last_activity', '>', Carbon::now()->subSeconds(5));
    }

    /**
     * Update or create typing status for a user in an event
     */
    public static function updateTyping($userId, $eventId)
    {
        return static::updateOrCreate(
            [
                'user_id' => $userId,
                'group_event_id' => $eventId,
            ],
            [
                'last_activity' => Carbon::now(),
            ]
        );
    }

    /**
     * Clean up old typing statuses
     */
    public static function cleanup()
    {
        return static::where('last_activity', '<', Carbon::now()->subSeconds(10))->delete();
    }
}
