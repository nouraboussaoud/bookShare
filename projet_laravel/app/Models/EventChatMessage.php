<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_event_id',
        'user_id',
        'message',
        'is_moderated',
        'ai_used',
        'moderation_reason',
        'moderation_status',
        'reply_to_message_id',
        'reply_to_user',
        'reply_to_content',
    ];

    protected $casts = [
        'is_moderated' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get the event that owns this message
     */
    public function event()
    {
        return $this->belongsTo(GroupEvent::class, 'group_event_id');
    }

    /**
     * Get the user who sent this message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for approved messages only
     */
    public function scopeApproved($query)
    {
        return $query->where('moderation_status', 'approved');
    }

    /**
     * Scope for flagged messages
     */
    public function scopeFlagged($query)
    {
        return $query->where('moderation_status', 'flagged');
    }
}
