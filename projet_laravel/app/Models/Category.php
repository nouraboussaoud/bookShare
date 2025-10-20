<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'age_allowed',
        'color',
        'icon',
        'is_featured',
        'sort_order',
        'reading_tips',
        'popular_authors',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'popular_authors' => 'array',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function activeBooks(): HasMany
    {
        return $this->hasMany(Book::class)->where('status', 'AVAILABLE');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function categoryTags(): HasMany
    {
        return $this->hasMany(CategoryTag::class);
    }

    public function popularTags()
    {
        return $this->categoryTags()->popular(5);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getPopularAuthorsListAttribute()
    {
        return $this->popular_authors ? implode(', ', $this->popular_authors) : '';
    }

    public function getAgeDisplayAttribute()
    {
        return $this->age_allowed == 0 ? 'Tout âge' : $this->age_allowed . '+';
    }
}
