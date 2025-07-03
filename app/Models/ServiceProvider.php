<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceProvider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'stage_name',
        'bio',
        'short_description',
        'base_price',
        'price_unit',
        'features',
        'experience_level',
        'years_experience',
        'languages',
        'specialties',
        'equipment_owned',
        'equipment_provided',
        'min_booking_hours',
        'max_booking_hours',
        'availability',
        'portfolio_links',
        'rating',
        'total_reviews',
        'total_bookings',
        'badge',
        'badge_class',
        'sort_order',
        'featured',
        'status'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'features' => 'array',
        'languages' => 'array',
        'specialties' => 'array',
        'equipment_owned' => 'array',
        'availability' => 'array',
        'portfolio_links' => 'array',
        'equipment_provided' => 'boolean',
        'featured' => 'boolean',
        'rating' => 'decimal:2',
        'years_experience' => 'integer',
        'min_booking_hours' => 'integer',
        'max_booking_hours' => 'integer',
        'total_reviews' => 'integer',
        'total_bookings' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Get the category that owns the service provider
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Get the pricing tiers
     */
    public function pricingTiers(): HasMany
    {
        return $this->hasMany(ServiceProviderPricing::class)->orderBy('sort_order');
    }

    /**
     * Get the media items
     */
    public function mediaItems(): HasMany
    {
        return $this->hasMany(ServiceProviderMedia::class)->orderBy('sort_order');
    }

    /**
     * Get the reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceProviderReview::class);
    }

    /**
     * Get approved reviews
     */
    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByExperience($query, $level)
    {
        return $query->where('experience_level', $level);
    }

    public function scopeTopRated($query)
    {
        return $query->where('rating', '>=', 4.0)->orderBy('rating', 'desc');
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('portfolio')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/mpeg']);
    }

    /**
     * Get profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        $media = $this->getFirstMedia('profile');
        if ($media) {
            return $media->getUrl();
        }

        // Return placeholder based on category
        $category = $this->category;
        if ($category) {
            return $category->image_url;
        }

        return 'https://via.placeholder.com/400x400';
    }

    /**
     * Get display name (stage name or regular name)
     */
    public function getDisplayNameAttribute()
    {
        return $this->stage_name ?: $this->name;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'LKR ' . number_format($this->base_price) . '/' . $this->price_unit;
    }

    /**
     * Check if available on a specific date
     */
    public function isAvailableOn($date)
    {
        // TODO: Implement availability checking logic
        // This would check against existing bookings and provider's availability settings
        return true;
    }

    /**
     * Get booking items
     */
    public function bookingItems()
    {
        return $this->morphMany(BookingItem::class, 'item');
    }
}