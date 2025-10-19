<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'reading_group_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'is_locked',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    /**
     * Get the reading group that owns this discussion
     */
    public function readingGroup()
    {
        return $this->belongsTo(ReadingGroup::class);
    }

    /**
     * Get the user who created this discussion
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all replies for this discussion
     */
    public function replies()
    {
        return $this->hasMany(GroupDiscussionReply::class);
    }

    /**
     * Get the count of replies
     */
    public function repliesCount()
    {
        return $this->replies()->count();
    }

    /**
     * Get the latest reply
     */
    public function latestReply()
    {
        return $this->replies()->latest()->first();
    }
}
