<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'sort_order',
        'show_on_homepage',
        'status'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'show_on_homepage' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the products for the category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get icon with default
     */
    public function getIconAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Default icons based on slug
        $iconMap = [
            'sound-equipment' => 'fas fa-volume-up',
            'lighting' => 'fas fa-lightbulb',
            'led-screens' => 'fas fa-tv',
            'dj-equipment' => 'fas fa-headphones',
            'backdrops' => 'fas fa-image',
            'tables-chairs' => 'fas fa-chair',
            'tents-canopy' => 'fas fa-campground',
            'photo-booths' => 'fas fa-camera',
            'power-distribution' => 'fas fa-bolt',
            'dance-floors' => 'fas fa-compact-disc',
            'trusses' => 'fas fa-project-diagram',
            'led-tvs' => 'fas fa-desktop',
            'event-props' => 'fas fa-magic',
            'decoration-items' => 'fas fa-star',
            'band-equipment' => 'fas fa-guitar',
            'launching-gimmicks' => 'fas fa-rocket',
        ];

        return $iconMap[$this->slug] ?? 'fas fa-box';
    }
}