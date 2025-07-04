<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'customer_type' => ['required', 'in:individual,corporate'],
            'company' => ['required_if:customer_type,corporate', 'nullable', 'string', 'max:255'],
            'company_registration' => ['required_if:customer_type,corporate', 'nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'newsletter' => ['boolean'],
            'terms' => ['required', 'accepted']
        ], [
            'company.required_if' => 'Company name is required for corporate accounts.',
            'company_registration.required_if' => 'Company registration number is required for corporate accounts.',
            'terms.accepted' => 'You must agree to the terms and conditions.'
        ]);

        DB::beginTransaction();
        
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Create customer profile
            $customer = Customer::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'customer_type' => $validated['customer_type'],
                'company' => $validated['company'] ?? null,
                'company_registration' => $validated['company_registration'] ?? null,
                'newsletter_subscribed' => $request->boolean('newsletter'),
                'sms_notifications' => true, // Default to true
            ]);

            DB::commit();

            // Fire registered event
            event(new Registered($user));

            // Log the user in
            Auth::login($user);

            // Preserve cart items if any
            if (session()->has('cart')) {
                $request->session()->regenerate();
            }

            // Redirect based on intended destination or cart contents
            if (session()->has('cart') && count(session('cart')) > 0) {
                return redirect()->route('checkout.event-details')
                    ->with('success', 'Account created successfully! Please continue with your booking.');
            }

            return redirect()->route('account.dashboard')
                ->with('success', 'Welcome to KL Mobile! Your account has been created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'An error occurred while creating your account. Please try again.']);
        }
    }
}