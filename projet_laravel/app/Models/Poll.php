<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'type', // 'yes_no', 'multiple_choice', 'rating'
        'is_active',
        'closes_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'closes_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the event that owns this poll
     */
    public function event()
    {
        return $this->belongsTo(GroupEvent::class, 'event_id');
    }

    /**
     * Get the user who created this poll
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all poll options
     */
    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    /**
     * Get all votes for this poll
     */
    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    /**
     * Get votes for a specific option
     */
    public function votesForOption(PollOption $option)
    {
        return $this->votes()->where('poll_option_id', $option->id);
    }

    /**
     * Get rating votes
     */
    public function ratingVotes()
    {
        return $this->votes()->whereNotNull('rating_value');
    }

    /**
     * Check if poll is still active (can be voted on)
     */
    public function isActive()
    {
        return $this->is_active && ($this->closes_at === null || $this->closes_at->isFuture());
    }

    /**
     * Get poll results aggregated
     */
    public function getResults()
    {
        $results = [
            'type' => $this->type,
            'total_votes' => $this->votes()->count(),
            'data' => [],
        ];

        if ($this->type === 'rating') {
            $ratings = $this->ratingVotes()
                ->selectRaw('rating_value, COUNT(*) as count')
                ->groupBy('rating_value')
                ->get()
                ->pluck('count', 'rating_value')
                ->toArray();

            for ($i = 1; $i <= 5; $i++) {
                $results['data'][$i] = $ratings[$i] ?? 0;
            }

            $results['average'] = $this->ratingVotes()->avg('rating_value');
        } else {
            foreach ($this->options as $option) {
                $voteCount = $option->votes()->count();
                $results['data'][$option->id] = [
                    'text' => $option->text,
                    'votes' => $voteCount,
                    'percentage' => $results['total_votes'] > 0 
                        ? round(($voteCount / $results['total_votes']) * 100, 2)
                        : 0,
                ];
            }
        }

        return $results;
    }

    /**
     * Close the poll
     */
    public function close()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Get user's vote on this poll (if exists)
     */
    public function getUserVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    /**
     * Check if user has already voted
     */
    public function userHasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
}
