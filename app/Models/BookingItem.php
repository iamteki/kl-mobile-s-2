<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'item_type',
        'item_id',
        'item_name',
        'item_sku',
        'product_variation_id',
        'variation_name',
        'quantity',
        'unit_price',
        'total_price',
        'rental_days',
        'selected_addons',
        'addons_price',
        'status',
        'delivered_at',
        'returned_at',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'rental_days' => 'integer',
        'selected_addons' => 'array',
        'addons_price' => 'decimal:2',
        'delivered_at' => 'datetime',
        'returned_at' => 'datetime'
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    // Polymorphic relation
    public function item()
    {
        return $this->morphTo('item', 'item_type', 'item_id');
    }

    // Accessor to get the actual item based on item_type
    public function getActualItemAttribute()
    {
        switch ($this->item_type) {
            case 'product':
                return Product::find($this->item_id);
            case 'service_provider':
                return ServiceProvider::find($this->item_id);
            case 'package':
                return Package::find($this->item_id);
            default:
                return null;
        }
    }

    // Helper method to get item details
    public function getItemDetailsAttribute()
    {
        $item = $this->actual_item;
        if (!$item) {
            return [
                'name' => $this->item_name,
                'image' => 'https://via.placeholder.com/100',
                'category' => $this->item_type
            ];
        }

        switch ($this->item_type) {
            case 'product':
                return [
                    'name' => $item->name,
                    'image' => $item->main_image_url ?? 'https://via.placeholder.com/100',
                    'category' => $item->category->name ?? 'Equipment'
                ];
            case 'service_provider':
                return [
                    'name' => $item->name,
                    'image' => $item->profile_image_url ?? 'https://via.placeholder.com/100',
                    'category' => $item->category->name ?? 'Service'
                ];
            case 'package':
                return [
                    'name' => $item->name,
                    'image' => $item->image ?? 'https://via.placeholder.com/100',
                    'category' => 'Package'
                ];
            default:
                return [
                    'name' => $this->item_name,
                    'image' => 'https://via.placeholder.com/100',
                    'category' => $this->item_type
                ];
        }
    }
}