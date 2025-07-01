<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Carbon\Carbon;

class PricingService
{
    /**
     * Get pricing tiers for a product
     */
    public function getPricingTiers($product)
    {
        $basePrice = $product->base_price;
        
        return [
            [
                'days' => '1-2',
                'price' => $basePrice,
                'price_per_day' => $basePrice,
                'discount' => 0,
                'savings' => 0,
                'label' => 'Standard Rate'
            ],
            [
                'days' => '3-5',
                'price' => $basePrice * 0.9,
                'price_per_day' => $basePrice * 0.9,
                'discount' => 10,
                'savings' => 10,
                'label' => '10% Off'
            ],
            [
                'days' => '6+',
                'price' => $basePrice * 0.8,
                'price_per_day' => $basePrice * 0.8,
                'discount' => 20,
                'savings' => 20,
                'label' => '20% Off'
            ]
        ];
    }
    
    /**
     * Calculate price for a rental
     */
    public function calculatePrice($product, $startDate, $endDate, $quantity = 1, $variationId = null)
    {
        // Get base price
        if ($variationId) {
            $variation = ProductVariation::find($variationId);
            $basePrice = $variation ? $variation->price : $product->base_price;
        } else {
            $basePrice = $product->base_price;
        }
        
        // Calculate rental days
        $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        
        // Apply tier pricing
        $pricePerDay = $this->getTierPrice($basePrice, $days);
        
        // Calculate totals
        $subtotal = $pricePerDay * $days * $quantity;
        $discount = $basePrice != $pricePerDay ? (($basePrice - $pricePerDay) * $days * $quantity) : 0;
        
        return [
            'base_price' => $basePrice,
            'price_per_day' => $pricePerDay,
            'days' => $days,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal,
            'savings_percentage' => $discount > 0 ? round(($discount / ($basePrice * $days * $quantity)) * 100) : 0
        ];
    }
    
    /**
     * Get tier price based on rental days
     */
    private function getTierPrice($basePrice, $days)
    {
        if ($days >= 6) {
            return $basePrice * 0.8; // 20% off
        } elseif ($days >= 3) {
            return $basePrice * 0.9; // 10% off
        }
        
        return $basePrice;
    }
    
    /**
     * Calculate cart total with discounts
     */
    public function calculateCartTotal($items, $eventDate, $eventEndDate = null)
    {
        $subtotal = 0;
        $totalDiscount = 0;
        
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;
            
            $pricing = $this->calculatePrice(
                $product,
                $eventDate,
                $eventEndDate ?? $eventDate,
                $item['quantity'],
                $item['variation_id'] ?? null
            );
            
            $subtotal += $pricing['subtotal'];
            $totalDiscount += $pricing['discount'];
        }
        
        return [
            'subtotal' => $subtotal,
            'discount' => $totalDiscount,
            'total' => $subtotal - $totalDiscount
        ];
    }
    
    /**
     * Get package pricing with discount
     */
    public function getPackagePricing($package, $eventDate, $eventEndDate = null)
    {
        $days = 1;
        if ($eventEndDate) {
            $days = Carbon::parse($eventDate)->diffInDays(Carbon::parse($eventEndDate)) + 1;
        }
        
        // Apply tier discount to packages too
        $basePrice = $package->price;
        $pricePerDay = $this->getTierPrice($basePrice, $days);
        
        return [
            'base_price' => $basePrice,
            'price_per_day' => $pricePerDay,
            'days' => $days,
            'total' => $pricePerDay * $days,
            'savings' => $basePrice != $pricePerDay ? round(((($basePrice - $pricePerDay) * $days) / ($basePrice * $days)) * 100) : 0
        ];
    }
    
    /**
     * Calculate addon pricing
     */
    public function calculateAddonPricing($addons, $days = 1)
    {
        $total = 0;
        $breakdown = [];
        
        foreach ($addons as $addon) {
            $addonPrice = $addon['price'] * $days;
            $total += $addonPrice;
            
            $breakdown[] = [
                'name' => $addon['name'],
                'price' => $addon['price'],
                'days' => $days,
                'total' => $addonPrice
            ];
        }
        
        return [
            'total' => $total,
            'breakdown' => $breakdown
        ];
    }
}