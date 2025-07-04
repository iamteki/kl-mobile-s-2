<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'times_used',
        'usage_limit_per_customer',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_categories',
        'applicable_products'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
        'usage_limit' => 'integer',
        'times_used' => 'integer',
        'usage_limit_per_customer' => 'integer'
    ];

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->valid_from > $now) {
            return false;
        }
        
        if ($this->valid_until && $this->valid_until < $now) {
            return false;
        }
        
        if ($this->usage_limit && $this->times_used >= $this->usage_limit) {
            return false;
        }
        
        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return 0;
        }
        
        $discount = 0;
        
        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);
            
            if ($this->maximum_discount) {
                $discount = min($discount, $this->maximum_discount);
            }
        } else {
            $discount = min($this->discount_value, $amount);
        }
        
        return $discount;
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }

    /**
     * Get discount display text
     */
    public function getDiscountDisplayAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '% OFF';
        }
        
        return 'LKR ' . number_format($this->discount_value, 2) . ' OFF';
    }
}