<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDiscussionReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_discussion_id',
        'user_id',
        'content',
    ];

    /**
     * Get the discussion that owns this reply
     */
    public function discussion()
    {
        return $this->belongsTo(GroupDiscussion::class, 'group_discussion_id');
    }

    /**
     * Get the user who created this reply
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
