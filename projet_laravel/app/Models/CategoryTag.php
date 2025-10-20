<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryTag extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'type',
        'usage_count',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Relationship: Tag belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Tag can be applied to many books
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_category_tag')
            ->withPivot('created_by_user_id')
            ->withTimestamps();
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement usage count
     */
    public function decrementUsage()
    {
        $this->decrement('usage_count');
    }

    /**
     * Scope: Get tags by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get popular tags (most used)
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }

    /**
     * Scope: Get tags for a specific category
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get badge HTML
     */
    public function getBadgeHtmlAttribute()
    {
        $icon = $this->icon ? "<i class='{$this->icon}'></i> " : '';
        return "<span class='badge' style='background-color: {$this->color}; color: white;'>{$icon}{$this->name}</span>";
    }
}
