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
}