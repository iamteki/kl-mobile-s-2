<?php

namespace App\Observers;

use App\Models\BookingItem;
use App\Models\Booking;

class BookingItemObserver
{
    /**
     * Handle the BookingItem "updated" event.
     */
    public function updated(BookingItem $bookingItem): void
    {
        if ($bookingItem->isDirty('status')) {
            $this->updateBookingDeliveryStatus($bookingItem->booking);
        }
    }
    
    /**
     * Update booking delivery status based on items
     */
    protected function updateBookingDeliveryStatus(Booking $booking): void
    {
        $items = $booking->items;
        $allDelivered = $items->every(fn ($item) => $item->status === 'delivered');
        $allReturned = $items->every(fn ($item) => $item->status === 'returned');
        $someDelivered = $items->contains(fn ($item) => $item->status === 'delivered');
        
        if ($allReturned) {
            $booking->update(['delivery_status' => 'picked_up']);
        } elseif ($allDelivered) {
            $booking->update(['delivery_status' => 'delivered']);
        } elseif ($someDelivered) {
            $booking->update(['delivery_status' => 'scheduled']);
        } else {
            $booking->update(['delivery_status' => 'pending']);
        }
        
        // Also update booking status if needed
        if ($allDelivered && $booking->booking_status === 'confirmed') {
            $booking->update(['booking_status' => 'in_progress']);
        } elseif ($allReturned && $booking->booking_status === 'in_progress') {
            $booking->update(['booking_status' => 'completed']);
        }
    }
}