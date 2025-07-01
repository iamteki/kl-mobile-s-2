<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Booking;
use App\Models\BookingItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AvailabilityService
{
    /**
     * Check if a product is available for the given dates and quantity
     */
    public function checkAvailability($productId, $variationId, $startDate, $endDate, $quantity)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->status !== 'active') {
            return false;
        }
        
        // Get the total quantity available
        if ($variationId) {
            $variation = ProductVariation::find($variationId);
            $totalQuantity = $variation ? $variation->available_quantity : 0;
        } else {
            $totalQuantity = $product->available_quantity;
        }
        
        // Get booked quantity for the date range
        $bookedQuantity = $this->getBookedQuantity($productId, $variationId, $startDate, $endDate);
        
        // Check if requested quantity is available
        $availableQuantity = $totalQuantity - $bookedQuantity;
        
        return $availableQuantity >= $quantity;
    }
    
    /**
     * Get booked quantity for a product in a date range
     */
    public function getBookedQuantity($productId, $variationId, $startDate, $endDate)
    {
        return BookingItem::whereHas('booking', function ($query) use ($startDate, $endDate) {
                $query->whereNotIn('booking_status', ['cancelled', 'failed'])
                      ->where(function ($q) use ($startDate, $endDate) {
                          // Check if booking rental period overlaps with requested dates
                          $q->where(function ($q1) use ($startDate, $endDate) {
                              // Booking starts within the requested period
                              $q1->whereBetween('rental_start_date', [$startDate, $endDate]);
                          })->orWhere(function ($q2) use ($startDate, $endDate) {
                              // Booking ends within the requested period
                              $q2->whereBetween('rental_end_date', [$startDate, $endDate]);
                          })->orWhere(function ($q3) use ($startDate, $endDate) {
                              // Booking spans the entire requested period
                              $q3->where('rental_start_date', '<=', $startDate)
                                 ->where('rental_end_date', '>=', $endDate);
                          });
                      });
            })
            ->where('item_type', 'product')
            ->where('item_id', $productId)
            ->when($variationId, function ($query) use ($variationId) {
                return $query->where('product_variation_id', $variationId);
            })
            ->whereNotIn('status', ['returned', 'cancelled'])
            ->sum('quantity');
    }
    
    /**
     * Get availability calendar data for a product
     */
    public function getCalendarData($productId, $startDate, $endDate)
    {
        $product = Product::find($productId);
        if (!$product) {
            return [];
        }
        
        $calendarData = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $bookedQuantity = $this->getBookedQuantity($productId, null, $dateStr, $dateStr);
            $availableQuantity = $product->available_quantity - $bookedQuantity;
            
            $calendarData[$dateStr] = [
                'date' => $dateStr,
                'available' => $availableQuantity > 0,
                'quantity' => $availableQuantity,
                'status' => $this->getAvailabilityStatus($availableQuantity, $product->available_quantity),
                'is_past' => $currentDate->isPast(),
                'is_today' => $currentDate->isToday()
            ];
            
            $currentDate->addDay();
        }
        
        return $calendarData;
    }
    
    /**
     * Get availability status based on quantity
     */
    private function getAvailabilityStatus($available, $total)
    {
        if ($available <= 0) {
            return 'unavailable';
        } elseif ($available < ($total * 0.3)) {
            return 'limited';
        } else {
            return 'available';
        }
    }
    
    /**
     * Check multiple products availability
     */
    public function checkMultipleAvailability($items, $eventDate, $eventEndDate = null)
    {
        $results = [];
        $endDate = $eventEndDate ?? $eventDate;
        
        foreach ($items as $item) {
            $available = $this->checkAvailability(
                $item['product_id'],
                $item['variation_id'] ?? null,
                $eventDate,
                $endDate,
                $item['quantity']
            );
            
            $results[$item['product_id']] = $available;
        }
        
        return $results;
    }
    
    /**
     * Get booked items for a specific date range
     */
    public function getBookedItemsForDateRange($startDate, $endDate)
    {
        return BookingItem::with(['booking', 'product'])
            ->whereHas('booking', function ($query) use ($startDate, $endDate) {
                $query->whereNotIn('booking_status', ['cancelled', 'failed'])
                      ->where(function ($q) use ($startDate, $endDate) {
                          $q->whereBetween('rental_start_date', [$startDate, $endDate])
                            ->orWhereBetween('rental_end_date', [$startDate, $endDate])
                            ->orWhere(function ($q2) use ($startDate, $endDate) {
                                $q2->where('rental_start_date', '<=', $startDate)
                                   ->where('rental_end_date', '>=', $endDate);
                            });
                      });
            })
            ->where('item_type', 'product')
            ->whereNotIn('status', ['returned', 'cancelled'])
            ->get()
            ->groupBy('item_id');
    }
    
    /**
     * Reserve inventory for a booking
     */
    public function reserveInventory($bookingId, $items)
    {
        $results = [];
        
        foreach ($items as $item) {
            if ($item['item_type'] !== 'product') {
                continue;
            }
            
            $available = $this->checkAvailability(
                $item['item_id'],
                $item['product_variation_id'] ?? null,
                $item['rental_start_date'],
                $item['rental_end_date'],
                $item['quantity']
            );
            
            if ($available) {
                // Here you would typically create inventory transaction records
                // For now, we'll just mark it as reserved
                $results[$item['item_id']] = [
                    'reserved' => true,
                    'quantity' => $item['quantity']
                ];
            } else {
                $results[$item['item_id']] = [
                    'reserved' => false,
                    'reason' => 'Insufficient inventory'
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Get availability summary for multiple products
     */
    public function getAvailabilitySummary($productIds, $startDate, $endDate)
    {
        $summary = [];
        
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }
            
            $bookedQuantity = $this->getBookedQuantity($productId, null, $startDate, $endDate);
            $availableQuantity = $product->available_quantity - $bookedQuantity;
            
            $summary[$productId] = [
                'product_name' => $product->name,
                'total_quantity' => $product->available_quantity,
                'booked_quantity' => $bookedQuantity,
                'available_quantity' => $availableQuantity,
                'availability_percentage' => $product->available_quantity > 0 
                    ? round(($availableQuantity / $product->available_quantity) * 100) 
                    : 0,
                'status' => $this->getAvailabilityStatus($availableQuantity, $product->available_quantity)
            ];
        }
        
        return $summary;
    }
}