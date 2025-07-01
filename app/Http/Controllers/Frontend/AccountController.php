<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $recentBookings = Booking::where('customer_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        $stats = [
            'total_bookings' => Booking::where('customer_id', $user->id)->count(),
            'upcoming_events' => Booking::where('customer_id', $user->id)
                ->where('event_date', '>=', now())
                ->count(),
            'total_spent' => Booking::where('customer_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total')
        ];
        
        return view('frontend.account.dashboard', compact('user', 'recentBookings', 'stats'));
    }
    
    public function bookings()
    {
        $bookings = Booking::where('customer_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('frontend.account.bookings', compact('bookings'));
    }
    
    public function bookingDetails(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->customer_id !== auth()->user()->id) {
            abort(403);
        }
        
        return view('frontend.account.booking-details', compact('booking'));
    }
    
    public function downloadInvoice(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->customer_id !== auth()->user()->id) {
            abort(403);
        }
        
        // Generate and download invoice PDF
        // For now, just redirect back
        return back()->with('info', 'Invoice download feature coming soon!');
    }
    
    public function profile()
    {
        $user = auth()->user();
        return view('frontend.account.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255'
        ]);
        
        $user = auth()->user();
        $user->update($request->only('name', 'email'));
        
        // Update or create customer profile
        $user->customer()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('phone', 'address', 'company')
        );
        
        return back()->with('success', 'Profile updated successfully!');
    }
    
    public function password()
    {
        return view('frontend.account.password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return back()->with('success', 'Password updated successfully!');
    }
}