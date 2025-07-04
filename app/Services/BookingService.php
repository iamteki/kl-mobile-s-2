<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;

class BookingService
{
    /**
     * Create a booking from cart data
     */
    public function createBooking(array $cart, array $eventDetails, array $customerInfo, User $user, string $paymentIntentId): Booking
    {
        return DB::transaction(function () use ($cart, $eventDetails, $customerInfo, $user, $paymentIntentId) {
            
            // Ensure user has customer profile
            if (!$user->customer) {
                $user->customer()->create([
                    'phone' => $customerInfo['phone'],
                    'address' => $customerInfo['address'],
                    'company' => $customerInfo['company'] ?? null,
                ]);
            }
            
            // Calculate rental period
            $eventDate = Carbon::parse($eventDetails['event_date']);
            $rentalStartDate = Carbon::parse($eventDetails['rental_start_date'] ?? $eventDate);
            $rentalEndDate = Carbon::parse($eventDetails['rental_end_date'] ?? $eventDate);
            $rentalDays = max(1, $rentalStartDate->diffInDays($rentalEndDate) + 1);
            
            // Create booking
            $booking = Booking::create([
                'booking_number' => $this->generateBookingNumber(),
                'customer_id' => $user->customer->id,
                
                // Event Details
                'event_date' => $eventDetails['event_date'],
                'event_type' => $eventDetails['event_type'],
                'venue' => $eventDetails['venue'],
                'number_of_pax' => $eventDetails['number_of_pax'],
                'installation_time' => $eventDetails['installation_time'],
                'event_start_time' => $eventDetails['event_start_time'],
                'dismantle_time' => $eventDetails['dismantle_time'],
                
                // Rental Period
                'rental_start_date' => $rentalStartDate,
                'rental_end_date' => $rentalEndDate,
                'rental_days' => $rentalDays,
                
                // Pricing
                'subtotal' => $cart['subtotal'],
                'discount_amount' => $cart['discount'] ?? 0,
                'coupon_code' => $cart['coupon'] ?? null,
                'tax_amount' => $cart['tax'] ?? 0,
                'delivery_charge' => 500, // Flat rate for now
                'total' => $cart['total'] + 500, // Including delivery
                
                // Payment
                'payment_status' => 'paid',
                'payment_method' => 'stripe',
                'stripe_payment_intent_id' => $paymentIntentId,
                'paid_at' => now(),
                
                // Status
                'booking_status' => 'confirmed',
                
                // Delivery Details
                'delivery_address' => $customerInfo['delivery_address'],
                'delivery_instructions' => $customerInfo['delivery_instructions'] ?? null,
                'delivery_status' => 'pending',
                
                // Additional Information
                'special_requests' => $eventDetails['special_requests'] ?? null,
                
                // Customer Details (denormalized for historical record)
                'customer_name' => $customerInfo['name'],
                'customer_email' => $customerInfo['email'],
                'customer_phone' => $customerInfo['phone'],
                'customer_company' => $customerInfo['company'] ?? null,
            ]);
            
            // Create booking items
            foreach ($cart['items'] as $item) {
                $bookingItem = [
                    'booking_id' => $booking->id,
                    'item_type' => $item['type'],
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $item['price'],
                    'rental_days' => $item['rental_days'] ?? $rentalDays,
                    'status' => 'pending',
                ];
                
                // Set specific fields based on item type
                switch ($item['type']) {
                    case 'product':
                        $bookingItem['item_id'] = $item['product_id'];
                        $bookingItem['item_name'] = $item['name'];
                        $bookingItem['product_variation_id'] = $item['variation_id'] ?? null;
                        $bookingItem['total_price'] = $item['price'] * $item['quantity'] * ($item['rental_days'] ?? 1);
                        break;
                        
                    case 'service_provider':
                        $bookingItem['item_id'] = $item['provider_id'];
                        $bookingItem['item_name'] = $item['name'];
                        $bookingItem['total_price'] = $item['price'];
                        $bookingItem['selected_addons'] = json_encode([
                            'pricing_tier_id' => $item['pricing_tier_id'] ?? null,
                            'duration' => $item['duration'] ?? null,
                            'start_time' => $item['start_time'] ?? null,
                        ]);
                        break;
                        
                    case 'package':
                        $bookingItem['item_id'] = $item['package_id'];
                        $bookingItem['item_name'] = $item['name'];
                        $bookingItem['total_price'] = $item['price'] * ($item['quantity'] ?? 1);
                        break;
                }
                
                BookingItem::create($bookingItem);
            }
            
            // Update customer stats
            $customer = $user->customer;
            $customer->increment('total_bookings');
            $customer->increment('total_spent', $booking->total);
            $customer->update(['last_booking_date' => now()]);
            
            return $booking;
        });
    }
    
    /**
     * Generate unique booking number
     */
    private function generateBookingNumber(): string
    {
        $prefix = 'KLM';
        $year = date('Y');
        
        do {
            $number = $prefix . '-' . $year . '-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_number', $number)->exists());
        
        return $number;
    }
    
    /**
     * Send booking confirmation email
     */
    public function sendConfirmationEmail(Booking $booking): void
    {
        try {

            Mail::to($booking->customer_email)->queue(new BookingConfirmation($booking));
            
           
            \Log::info('Booking confirmation email would be sent to: ' . $booking->customer_email);
        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculate available inventory for a given date
     */
    public function checkAvailability($productId, $date, $quantity = 1): bool
    {
        // Implement inventory checking logic
        // For now, return true
        return true;
    }
    
    /**
     * Update booking status
     */
    public function updateBookingStatus(Booking $booking, string $status): bool
    {
        return $booking->update(['booking_status' => $status]);
    }
    
    /**
     * Cancel booking
     */
    public function cancelBooking(Booking $booking, string $reason = null): bool
    {
        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return false;
        }
        
        return DB::transaction(function () use ($booking, $reason) {
            // Update booking status
            $booking->update([
                'booking_status' => 'cancelled',
                'internal_notes' => $reason
            ]);
            
            // Update booking items status
            $booking->items()->update(['status' => 'cancelled']);
            
            // TODO: Process refund if payment was made
            
            return true;
        });
    }
}