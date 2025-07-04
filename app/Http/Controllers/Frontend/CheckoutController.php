<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\BookingService;
use App\Models\Booking;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $bookingService;
    
    public function __construct(CartService $cartService, BookingService $bookingService)
    {
        $this->cartService = $cartService;
        $this->bookingService = $bookingService;
        // Note: In Laravel 12, middleware is handled in routes, not in constructor
    }
    
    /**
     * Step 1: Event Details
     */
    public function eventDetails()
    {
        if (!$this->cartService->hasItems()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add items before checkout.');
        }
        
        $cart = $this->cartService->getCart();
        $eventTypes = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday Party',
            'corporate' => 'Corporate Event',
            'concert' => 'Concert/Festival',
            'private' => 'Private Party',
            'religious' => 'Religious Event',
            'graduation' => 'Graduation',
            'other' => 'Other'
        ];
        
        // Get any existing event details from session
        $eventDetails = session('checkout.event_details', []);
        
        return view('frontend.checkout.event-details', compact('cart', 'eventTypes', 'eventDetails'));
    }
    
    /**
     * Store Event Details
     */
    public function storeEventDetails(Request $request)
    {
        $validated = $request->validate([
            'event_date' => 'required|date|after:today',
            'event_type' => 'required|string|in:wedding,birthday,corporate,concert,private,religious,graduation,other',
            'venue' => 'required|string|max:500',
            'number_of_pax' => 'required|integer|min:1|max:10000',
            'installation_time' => 'required|date_format:H:i',
            'event_start_time' => 'required|date_format:H:i|after:installation_time',
            'dismantle_time' => 'required|date_format:H:i|after:event_start_time',
            'special_requests' => 'nullable|string|max:1000'
        ], [
            'event_date.after' => 'Event date must be at least tomorrow.',
            'event_start_time.after' => 'Event start time must be after installation time.',
            'dismantle_time.after' => 'Dismantle time must be after event start time.'
        ]);
        
        // Calculate rental period
        $eventDate = Carbon::parse($validated['event_date']);
        $rentalStartDate = $eventDate->copy();
        $rentalEndDate = $eventDate->copy();
        
        // Store event details in session
        session(['checkout.event_details' => array_merge($validated, [
            'rental_start_date' => $rentalStartDate->format('Y-m-d'),
            'rental_end_date' => $rentalEndDate->format('Y-m-d'),
            'rental_days' => 1 // Single day rental by default
        ])]);
        
        // Update cart items with event date if needed
        $this->cartService->updateEventDate($validated['event_date']);
        
        return redirect()->route('checkout.customer-info');
    }
    
    /**
     * Step 2: Customer Information
     */
    public function customerInfo()
    {
        if (!session()->has('checkout.event_details')) {
            return redirect()->route('checkout.event-details')
                ->with('error', 'Please complete event details first.');
        }
        
        $cart = $this->cartService->getCart();
        $user = auth()->user();
        $customer = $user->customer;
        
        // Pre-fill customer info from profile
        $customerInfo = session('checkout.customer_info', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $customer?->phone ?? '',
            'address' => $customer?->address ?? '',
            'company' => $customer?->company ?? '',
            'delivery_address' => '',
            'delivery_instructions' => ''
        ]);
        
        return view('frontend.checkout.customer-info', compact('cart', 'user', 'customerInfo'));
    }
    
    /**
     * Store Customer Information
     */
    public function storeCustomerInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'company' => 'nullable|string|max:255',
            'use_different_delivery' => 'boolean',
            'delivery_address' => 'required_if:use_different_delivery,true|nullable|string|max:500',
            'delivery_instructions' => 'nullable|string|max:500',
            'save_info' => 'boolean'
        ]);
        
        // Update user's customer profile if requested
        if ($request->boolean('save_info')) {
            $user = auth()->user();
            if (!$user->customer) {
                $user->customer()->create([
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'company' => $validated['company'] ?? null
                ]);
            } else {
                $user->customer->update([
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'company' => $validated['company'] ?? null
                ]);
            }
        }
        
        // Determine delivery address
        $deliveryAddress = $request->boolean('use_different_delivery') 
            ? $validated['delivery_address'] 
            : $validated['address'];
        
        // Store customer info in session
        session(['checkout.customer_info' => array_merge($validated, [
            'delivery_address' => $deliveryAddress
        ])]);
        
        return redirect()->route('checkout.payment');
    }
    
    /**
     * Step 3: Payment
     */
    public function payment()
    {
        if (!session()->has('checkout.customer_info')) {
            return redirect()->route('checkout.customer-info')
                ->with('error', 'Please complete customer information first.');
        }
        
        $cart = $this->cartService->getCart();
        $eventDetails = session('checkout.event_details');
        $customerInfo = session('checkout.customer_info');
        
        // Calculate final totals
        $subtotal = $cart['subtotal'];
        $discount = $cart['discount'] ?? 0;
        $tax = 0; // Implement tax calculation if needed
        $deliveryCharge = $this->calculateDeliveryCharge($customerInfo['delivery_address']);
        $total = $subtotal - $discount + $tax + $deliveryCharge;
        
        // Create Stripe Payment Intent
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => round($total * 100), // Amount in cents
                'currency' => 'lkr',
                'description' => 'KL Mobile Event Booking',
                'metadata' => [
                    'user_id' => auth()->id(),
                    'event_date' => $eventDetails['event_date'],
                    'event_type' => $eventDetails['event_type']
                ]
            ]);
            
            $clientSecret = $paymentIntent->client_secret;
            
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to initialize payment. Please try again.');
        }
        
        return view('frontend.checkout.payment', compact(
            'cart',
            'eventDetails',
            'customerInfo',
            'subtotal',
            'discount',
            'tax',
            'deliveryCharge',
            'total',
            'clientSecret'
        ));
    }
    
    /**
     * Process Payment and Create Booking
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'payment_method_id' => 'required|string',
            'terms' => 'required|accepted'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Verify payment with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            
            if ($paymentIntent->status !== 'succeeded') {
                // Attempt to confirm payment
                $paymentIntent->confirm([
                    'payment_method' => $request->payment_method_id
                ]);
            }
            
            // Create booking
            $cart = $this->cartService->getCart();
            $eventDetails = session('checkout.event_details');
            $customerInfo = session('checkout.customer_info');
            
            $booking = $this->bookingService->createBooking(
                $cart,
                $eventDetails,
                $customerInfo,
                auth()->user(),
                $paymentIntent->id
            );
            
            // Clear cart and session
            $this->cartService->clearCart();
            session()->forget(['checkout.event_details', 'checkout.customer_info']);
            
            DB::commit();
            
            // Send confirmation email (queue it)
            $this->bookingService->sendConfirmationEmail($booking);
            
            return redirect()->route('checkout.confirmation', $booking)
                ->with('success', 'Booking confirmed successfully!');
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollback();
            \Log::error('Stripe payment error: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'Payment failed: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Checkout error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }
    
    /**
     * Step 4: Booking Confirmation
     */
    public function confirmation(Booking $booking)
    {
        // Ensure user can only see their own booking confirmations
        if ($booking->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }
        
        // Load relationships (simplified to avoid polymorphic issues)
        $booking->load(['items']);
        
        return view('frontend.checkout.confirmation', compact('booking'));
    }
    
    /**
     * Calculate delivery charge based on address
     */
    protected function calculateDeliveryCharge($address)
    {
        // Implement your delivery charge logic
        // For now, return a flat rate
        return 500; // LKR 500
    }
}