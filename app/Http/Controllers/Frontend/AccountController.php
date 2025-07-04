<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use PDF;

class AccountController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        $customer = $user->customer;
        
        // If no customer profile exists, create one
        if (!$customer) {
            $customer = $user->customer()->create([
                'phone' => '',
                'address' => '',
            ]);
        }
        
        // Get recent bookings
        $recentBookings = Booking::where('customer_id', $customer->id)
            ->with(['items'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Get upcoming events
        $upcomingEvents = Booking::where('customer_id', $customer->id)
            ->where('event_date', '>=', now())
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->orderBy('event_date')
            ->limit(3)
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_bookings' => Booking::where('customer_id', $customer->id)->count(),
            'upcoming_events' => Booking::where('customer_id', $customer->id)
                ->where('event_date', '>=', now())
                ->whereIn('booking_status', ['confirmed', 'pending'])
                ->count(),
            'completed_events' => Booking::where('customer_id', $customer->id)
                ->where('booking_status', 'completed')
                ->count(),
            'total_spent' => Booking::where('customer_id', $customer->id)
                ->where('payment_status', 'paid')
                ->sum('total')
        ];
        
        return view('frontend.account.dashboard', compact('user', 'customer', 'recentBookings', 'upcomingEvents', 'stats'));
    }
    
    /**
     * Show all bookings
     */
    public function bookings(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;
        
        if (!$customer) {
            return redirect()->route('account.dashboard')
                ->with('error', 'Please complete your profile first.');
        }
        
        $query = Booking::where('customer_id', $customer->id)
            ->with(['items']);
        
        // Filter by status if provided
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('booking_status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('event_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->where('event_date', '<=', $request->to_date);
        }
        
        $bookings = $query->orderBy('event_date', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        return view('frontend.account.bookings.index', compact('bookings'));
    }
    
    /**
     * Show booking details
     */
    public function bookingDetails(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized access to booking.');
        }
        
        // Load relationships (simplified to avoid polymorphic issues)
        $booking->load(['items']);
        
        // Calculate time until event
        $daysUntilEvent = now()->diffInDays($booking->event_date, false);
        
        return view('frontend.account.bookings.show', compact('booking', 'daysUntilEvent'));
    }
    
    /**
     * Download booking invoice
     */
    public function downloadInvoice(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized access to invoice.');
        }
        
        // Load relationships
        $booking->load(['items', 'customer.user']);
        
        // Generate PDF
        $pdf = \PDF::loadView('frontend.account.invoice', compact('booking'));
        
        // Download the PDF
        return $pdf->download('invoice-' . $booking->booking_number . '.pdf');
    }
    
    /**
     * Show profile edit form
     */
    public function profile()
    {
        $user = auth()->user();
        $customer = $user->customer;
        
        return view('frontend.account.profile.edit', compact('user', 'customer'));
    }
    
    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'company' => 'nullable|string|max:255',
            'company_registration' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'newsletter_subscribed' => 'boolean',
            'sms_notifications' => 'boolean'
        ]);
        
        $user = auth()->user();
        
        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);
        
        // Update or create customer profile
        $customerData = [
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'company' => $validated['company'] ?? null,
            'company_registration' => $validated['company_registration'] ?? null,
            'tax_id' => $validated['tax_id'] ?? null,
            'newsletter_subscribed' => $request->boolean('newsletter_subscribed'),
            'sms_notifications' => $request->boolean('sms_notifications')
        ];
        
        if ($user->customer) {
            $user->customer->update($customerData);
        } else {
            $user->customer()->create($customerData);
        }
        
        return back()->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Show password change form
     */
    public function password()
    {
        return view('frontend.account.password');
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return back()->with('success', 'Password updated successfully!');
    }
    
    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if booking can be cancelled
        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }
        
        // Check cancellation deadline (48 hours before event)
        $hoursUntilEvent = now()->diffInHours($booking->event_date, false);
        if ($hoursUntilEvent < 48) {
            return back()->with('error', 'Bookings cannot be cancelled less than 48 hours before the event.');
        }
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);
        
        // Update booking status
        $booking->update([
            'booking_status' => 'cancelled',
            'internal_notes' => 'Customer cancellation: ' . $request->cancellation_reason
        ]);
        
        // TODO: Process refund if applicable
        
        return redirect()->route('account.bookings')
            ->with('success', 'Booking cancelled successfully. Refund will be processed within 5-7 business days.');
    }
}