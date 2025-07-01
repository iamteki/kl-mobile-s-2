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
}