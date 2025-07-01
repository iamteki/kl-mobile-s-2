<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\BookingService;
use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $bookingService;
    
    public function __construct(CartService $cartService, BookingService $bookingService)
    {
        $this->cartService = $cartService;
        $this->bookingService = $bookingService;
        
        $this->middleware('auth');
    }
    
    public function eventDetails()
    {
        if (!$this->cartService->hasItems()) {
            return redirect()->route('cart.index');
        }
        
        $cart = $this->cartService->getCart();
        
        return view('frontend.checkout.event-details', compact('cart'));
    }
    
    public function storeEventDetails(Request $request)
    {
        $request->validate([
            'event_date' => 'required|date|after:today',
            'event_type' => 'required|string',
            'venue' => 'required|string',
            'number_of_pax' => 'required|integer|min:1',
            'installation_time' => 'required',
            'event_start_time' => 'required',
            'dismantle_time' => 'required'
        ]);
        
        // Store event details in session
        session(['checkout.event_details' => $request->all()]);
        
        // Update cart with event date
        $this->cartService->updateEventDetails($request->event_date, $request->event_type, $request->venue);
        
        return redirect()->route('checkout.customer-info');
    }
    
    public function customerInfo()
    {
        if (!session()->has('checkout.event_details')) {
            return redirect()->route('checkout.event-details');
        }
        
        $cart = $this->cartService->getCart();
        $user = auth()->user();
        
        return view('frontend.checkout.customer-info', compact('cart', 'user'));
    }
    
    public function storeCustomerInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'company' => 'nullable|string',
            'special_requests' => 'nullable|string'
        ]);
        
        // Update user profile if needed
        $user = auth()->user();
        if (!$user->customer) {
            $user->customer()->create($request->only(['phone', 'address', 'company']));
        } else {
            $user->customer->update($request->only(['phone', 'address', 'company']));
        }
        
        // Store customer info in session
        session(['checkout.customer_info' => $request->all()]);
        
        return redirect()->route('checkout.payment');
    }
    
    public function payment()
    {
        if (!session()->has('checkout.customer_info')) {
            return redirect()->route('checkout.customer-info');
        }
        
        $cart = $this->cartService->getCart();
        $eventDetails = session('checkout.event_details');
        $customerInfo = session('checkout.customer_info');
        
        // Create payment intent
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $paymentIntent = PaymentIntent::create([
            'amount' => $cart['total'] * 100, // Amount in cents
            'currency' => 'lkr',
            'metadata' => [
                'user_id' => auth()->id(),
                'event_date' => $eventDetails['event_date']
            ]
        ]);
        
        return view('frontend.checkout.payment', compact(
            'cart',
            'eventDetails',
            'customerInfo',
            'paymentIntent'
        ));
    }
    
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'terms' => 'required|accepted'
        ]);
        
        try {
            // Create booking
            $booking = $this->bookingService->createBooking(
                $this->cartService->getCart(),
                session('checkout.event_details'),
                session('checkout.customer_info'),
                auth()->id()
            );
            
            // Process payment
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            $paymentIntent->confirm([
                'payment_method' => $request->payment_method_id
            ]);
            
            // Update booking status
            $booking->update([
                'payment_status' => 'paid',
                'stripe_payment_intent_id' => $paymentIntent->id
            ]);
            
            // Clear cart and session
            $this->cartService->clearCart();
            session()->forget(['checkout.event_details', 'checkout.customer_info']);
            
            // Send confirmation email
            $this->bookingService->sendConfirmationEmail($booking);
            
            return redirect()->route('checkout.confirmation', $booking);
            
        } catch (\Exception $e) {
            return back()->withErrors(['payment' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
    
    public function confirmation(Booking $booking)
    {
        // Ensure user can only see their own booking confirmations
        if ($booking->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }
        
        return view('frontend.checkout.confirmation', compact('booking'));
    }
}