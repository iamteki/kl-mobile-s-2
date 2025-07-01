<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_key',
        'attribute_value',
        'attribute_type',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Accessor to match what ProductController expects
    public function getAttributeNameAttribute()
    {
        return $this->attribute_key;
    }
    
    // You can also add this method to the model for clarity
    public function getAttributeValueAttribute()
    {
        return $this->attributes['attribute_value'];
    }
}