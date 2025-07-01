<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

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
     * Get a specific attribute value
     */
    public function getAttribute($key, $default = null)
    {
        // Check if it's a model attribute first
        if (array_key_exists($key, $this->attributes)) {
            return parent::getAttribute($key);
        }

        // Check custom attributes
        $attribute = $this->attributes()->where('attribute_key', $key)->first();
        return $attribute ? $attribute->attribute_value : $default;
    }

    /**
     * Get the first media URL (placeholder for now)
     */
    public function getFirstMediaUrl($collection = 'main')
    {
        // Placeholder implementation until media library is set up
        return $this->image ?? 'https://via.placeholder.com/400x300';
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
}