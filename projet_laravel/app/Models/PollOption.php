<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'text',
        'order',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the poll that owns this option
     */
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * Get all votes for this option
     */
    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    /**
     * Get vote count for this option
     */
    public function getVoteCount()
    {
        return $this->votes()->count();
    }
}
