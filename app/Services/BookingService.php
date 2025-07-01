<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\AvailabilityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    protected $cartService;
    protected $availabilityService;
    
    public function __construct(CartService $cartService, AvailabilityService $availabilityService)
    {
        $this->cartService = $cartService;
        $this->availabilityService = $availabilityService;
    }
    
    /**
     * Create a booking from cart
     */
    public function createBooking($userId, $eventDetails, $paymentIntentId)
    {
        return DB::transaction(function () use ($userId, $eventDetails, $paymentIntentId) {
            $cart = $this->cartService->getCart();
            
            // Create booking
            $booking = Booking::create([
                'booking_number' => $this->generateBookingNumber(),
                'user_id' => $userId,
                'event_date' => $eventDetails['event_date'],
                'event_type' => $eventDetails['event_type'],
                'venue' => $eventDetails['venue'],
                'number_of_pax' => $eventDetails['number_of_pax'],
                'installation_time' => $eventDetails['installation_time'],
                'event_start_time' => $eventDetails['event_start_time'],
                'dismantle_time' => $eventDetails['dismantle_time'],
                'subtotal' => $cart['total'],
                'discount' => $cart['discount'] ?? 0,
                'tax' => 0, // Calculate tax if needed
                'total' => $cart['total'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $eventDetails['notes'] ?? null
            ]);
            
            // Create booking items
            foreach ($cart['items'] as $item) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $item['variation_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }
            
            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'stripe',
                'transaction_id' => $paymentIntentId,
                'amount' => $booking->total,
                'status' => 'pending'
            ]);
            
            // Clear cart
            $this->cartService->clearCart();
            
            return $booking;
        });
    }
    
    /**
     * Generate unique booking number
     */
    private function generateBookingNumber()
    {
        do {
            $number = 'KLM-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_number', $number)->exists());
        
        return $number;
    }
    
    /**
     * Update booking status
     */
    public function updateBookingStatus($bookingId, $status)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => $status]);
        
        // Send notification email
        // TODO: Implement email notification
        
        return $booking;
    }
    
    /**
     * Confirm payment for booking
     */
    public function confirmPayment($bookingId, $paymentIntentId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        // Update booking status
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid'
        ]);
        
        // Update payment record
        $payment = $booking->payments()->where('transaction_id', $paymentIntentId)->first();
        if ($payment) {
            $payment->update(['status' => 'completed']);
        }
        
        return $booking;
    }
}