<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'brand',
        'subcategory',
        'short_description',
        'detailed_description',
        'base_price',
        'price_unit',
        'min_quantity',
        'max_quantity',
        'available_quantity',
        'sort_order',
        'featured',
        'included_items',
        'addons',
        'meta_title',
        'meta_description',
        'status',
        'image' // temporary field for image URL
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'featured' => 'boolean',
        'included_items' => 'array',
        'addons' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variations for the product
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the attributes for the product
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Get inventory for the product
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get booking items for the product
     */
    public function bookingItems()
    {
        return $this->morphMany(BookingItem::class, 'item');
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Get a specific attribute value
     */
    public function getProductAttribute($key, $default = null)
    {
        $attribute = $this->attributes()->where('attribute_key', $key)->first();
        return $attribute ? $attribute->attribute_value : $default;
    }

    /**
     * Get the first media URL with fallback
     */
    public function getFirstMediaUrl($collection = 'main', $conversion = ''): string
    {
        $media = $this->getFirstMedia($collection);
        
        if ($media) {
            return $conversion ? $media->getUrl($conversion) : $media->getUrl();
        }
        
        // Fallback to image field or placeholder
        return $this->image ?? 'https://via.placeholder.com/400x300';
    }

    /**
     * Get all media URLs for a collection
     */
    public function getMediaUrls($collection = 'gallery'): array
    {
        return $this->getMedia($collection)->map(function ($media) {
            return [
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'id' => $media->id
            ];
        })->toArray();
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'LKR ' . number_format($this->base_price);
    }

    /**
     * Check if product is available
     */
    public function isAvailable(): bool
    {
        return $this->available_quantity > 0 && $this->status === 'active';
    }




    // Add this method to your Product model class:

/**
 * Set the included_items attribute
 * Handle double-encoded JSON from database
 */
public function setIncludedItemsAttribute($value)
{
    if (is_array($value)) {
        $this->attributes['included_items'] = json_encode($value);
    } elseif (is_string($value)) {
        // Check if it's double-encoded
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_string($decoded)) {
            // It's double-encoded, decode again
            $this->attributes['included_items'] = $decoded;
        } else {
            $this->attributes['included_items'] = $value;
        }
    } else {
        $this->attributes['included_items'] = json_encode([]);
    }
}

/**
 * Get the included_items attribute
 * Always return as array
 */
public function getIncludedItemsAttribute($value)
{
    if (!$value) {
        return [];
    }

    // First decode
    $decoded = json_decode($value, true);
    
    // Check if it needs another decode (double-encoded)
    if (is_string($decoded)) {
        $decoded = json_decode($decoded, true);
    }
    
    return is_array($decoded) ? $decoded : [];
}


}