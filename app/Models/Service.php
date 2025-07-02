<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'starting_price',
        'price_unit',
        'features',
        'experience_level',
        'languages',
        'genres_specialties',
        'min_duration',
        'max_duration',
        'image',
        'badge',
        'badge_class',
        'equipment_included',
        'additional_charges',
        'sort_order',
        'featured',
        'status'
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'features' => 'array',
        'languages' => 'array',
        'genres_specialties' => 'array',
        'additional_charges' => 'array',
        'equipment_included' => 'boolean',
        'featured' => 'boolean',
        'min_duration' => 'integer',
        'max_duration' => 'integer',
        'sort_order' => 'integer'
    ];

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // If image starts with http, it's already a full URL
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            // Otherwise, it's a local file
            return asset('storage/' . $this->image);
        }
        
        // Return placeholder based on category
        $placeholders = [
            'Entertainment' => 'https://images.unsplash.com/photo-1571266028243-e4733b0f0bb0?w=600&h=400&fit=crop',
            'Technical Crew' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=600&h=400&fit=crop',
            'Media Production' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&h=400&fit=crop',
            'Event Staff' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=400&fit=crop',
        ];
        
        return $placeholders[$this->category] ?? 'https://via.placeholder.com/600x400';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Relationships
    public function bookings()
    {
        return $this->morphMany(BookingItem::class, 'item');
    }
}