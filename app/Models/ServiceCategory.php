<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'parent_category',
        'sort_order',
        'show_on_homepage',
        'status'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'show_on_homepage' => 'boolean',
    ];

    /**
     * Get the service providers for the category
     */
    public function serviceProviders(): HasMany
    {
        return $this->hasMany(ServiceProvider::class);
    }

    /**
     * Get active service providers
     */
    public function activeProviders(): HasMany
    {
        return $this->serviceProviders()->where('status', 'active');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for homepage categories
     */
    public function scopeShowOnHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    /**
     * Get categories by parent
     */
    public function scopeByParent($query, $parent)
    {
        return $query->where('parent_category', $parent);
    }

    /**
     * Get image URL attribute
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }
        
        // Default images based on category
        $defaults = [
            'professional-djs' => 'https://images.unsplash.com/photo-1571266028243-e4733b0f0bb0?w=600&h=400&fit=crop',
            'photographers' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&h=400&fit=crop',
            'videographers' => 'https://images.unsplash.com/photo-1606933248051-b3f30196e377?w=600&h=400&fit=crop',
            'event-emcees' => 'https://images.unsplash.com/photo-1560439514-4e9645039924?w=600&h=400&fit=crop',
        ];
        
        return $defaults[$this->slug] ?? 'https://via.placeholder.com/600x400';
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
            'professional-djs' => 'fas fa-headphones',
            'event-emcees' => 'fas fa-microphone',
            'live-bands' => 'fas fa-guitar',
            'sound-engineers' => 'fas fa-sliders-h',
            'lighting-technicians' => 'fas fa-lightbulb',
            'photographers' => 'fas fa-camera',
            'videographers' => 'fas fa-video',
            'event-coordinators' => 'fas fa-clipboard-list',
            'event-ushers' => 'fas fa-user-tie',
        ];

        return $iconMap[$this->slug] ?? 'fas fa-users';
    }

    /**
     * Get parent category display name
     */
    public function getParentCategoryDisplayAttribute()
    {
        $parentMap = [
            'entertainment' => 'Entertainment',
            'technical-crew' => 'Technical Crew',
            'media-production' => 'Media Production',
            'event-staff' => 'Event Staff',
        ];

        return $parentMap[$this->parent_category] ?? $this->parent_category;
    }
}