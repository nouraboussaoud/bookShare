<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reading_group_id',
        'role',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readingGroup()
    {
        return $this->belongsTo(ReadingGroup::class);
    }
}
