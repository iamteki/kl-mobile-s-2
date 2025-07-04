<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Rate limiting
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            RateLimiter::clear($this->throttleKey($request));
            
            // Update last login info
            Auth::user()->update(['last_login_at' => now()]);
            
            // Check if user has items in cart and was trying to checkout
            if ($request->session()->has('url.intended')) {
                $intended = $request->session()->get('url.intended');
                if (str_contains($intended, 'checkout')) {
                    return redirect($intended);
                }
            }
            
            // Check if user has cart items
            if (session()->has('cart') && count(session('cart')) > 0) {
                return redirect()->route('checkout.event-details')
                    ->with('info', 'Welcome back! You can now continue with your booking.');
            }
            
            return redirect()->intended(route('account.dashboard'));
        }

        // Increment rate limiter
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }
    
    /**
     * Validate login request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }
    
    /**
     * Ensure the request is not rate limited
     */
    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    
    /**
     * Get the rate limiting throttle key
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }
    
    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been successfully logged out.');
    }
}