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
}
