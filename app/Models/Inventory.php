<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'product_variation_id',
        'total_quantity',
        'available_quantity',
        'reserved_quantity',
        'maintenance_quantity',
        'location',
        'warehouse_section',
        'last_maintenance_date',
        'next_maintenance_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_quantity' => 'integer',
        'available_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'maintenance_quantity' => 'integer',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}