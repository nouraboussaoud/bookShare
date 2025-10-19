<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'is_private',
        'status',
        'max_members',
        'image',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    // Owner du groupe (un User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Toutes les adhésions
    public function memberships()
    {
        return $this->hasMany(GroupMembership::class);
    }

    // Les utilisateurs via la table membership
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
                    ->withPivot(['role', 'status', 'joined_at'])
                    ->withTimestamps();
    }

    // Events for this reading group
    public function events()
    {
        return $this->hasMany(GroupEvent::class);
    }

    // Upcoming events
    public function upcomingEvents()
    {
        return $this->events()
                    ->where('event_date', '>=', now()->toDateString())
                    ->orderBy('event_date')
                    ->orderBy('event_time');
    }

    // Past events
    public function pastEvents()
    {
        return $this->events()
                    ->where('event_date', '<', now()->toDateString())
                    ->orderByDesc('event_date')
                    ->orderByDesc('event_time');
    }

    // Discussions for this reading group
    public function discussions()
    {
        return $this->hasMany(GroupDiscussion::class);
    }

    // Active discussions (not locked)
    public function activeDiscussions()
    {
        return $this->discussions()
                    ->where('is_locked', false)
                    ->orderByDesc('is_pinned')
                    ->orderByDesc('updated_at');
    }

    // Pinned discussions
    public function pinnedDiscussions()
    {
        return $this->discussions()
                    ->where('is_pinned', true)
                    ->orderByDesc('updated_at');
    }
}
