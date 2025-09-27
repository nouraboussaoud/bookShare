<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'status',
        'category_id',
        'recommended_age',
        'photo',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getAgeDisplayAttribute()
    {
        return $this->recommended_age == 0 ? 'Tout âge' : $this->recommended_age . '+';
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAge($query, $age)
    {
        return $query->where('recommended_age', '<=', $age);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'AVAILABLE');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-book-cover.svg');
    }

    public function hasPhoto()
    {
        return !empty($this->photo) && file_exists(storage_path('app/public/' . $this->photo));
    }
}
