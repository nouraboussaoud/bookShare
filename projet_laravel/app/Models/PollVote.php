<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'poll_option_id',
        'user_id',
        'rating_value', // For rating polls (1-5)
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the poll that owns this vote
     */
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * Get the poll option that was voted for
     */
    public function option()
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }

    /**
     * Get the user who cast this vote
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
