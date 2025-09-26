<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'reportedUserId',
        'reporterId',
        'exchangeId',
        'status',
    ];

    // Relations
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reportedUserId');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporterId');
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchangeId');
    }
}