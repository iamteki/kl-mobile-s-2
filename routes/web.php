<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ServiceCategoryController;
use App\Http\Controllers\Frontend\ServiceProviderController;
use App\Http\Controllers\Frontend\PackageController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Equipment Categories & Products
Route::get('/equipment', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/equipment/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/equipment/{category}/{product}', [ProductController::class, 'show'])->name('product.show');

// Services - New Structure
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceCategoryController::class, 'index'])->name('services.index');
    Route::get('/{category}', [ServiceCategoryController::class, 'show'])->name('services.category');
    Route::get('/{category}/{provider}', [ServiceProviderController::class, 'show'])->name('services.provider');
    
    // AJAX routes
    Route::post('/provider/{provider}/check-availability', [ServiceProviderController::class, 'checkAvailability'])
        ->name('services.provider.check-availability');
});

// Packages
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('package.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Quick Booking
Route::get('/book-now', [BookingController::class, 'quick'])->name('booking.quick');

// Static Pages
Route::view('/about', 'frontend.pages.about')->name('about');
Route::view('/contact', 'frontend.pages.contact')->name('contact');
Route::view('/terms', 'frontend.pages.terms')->name('terms');
Route::view('/privacy', 'frontend.pages.privacy')->name('privacy');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Password Reset
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Checkout Process
    Route::prefix('checkout')->group(function () {
        Route::get('/event-details', [CheckoutController::class, 'eventDetails'])->name('checkout.event-details');
        Route::post('/event-details', [CheckoutController::class, 'storeEventDetails']);
        
        Route::get('/customer-info', [CheckoutController::class, 'customerInfo'])->name('checkout.customer-info');
        Route::post('/customer-info', [CheckoutController::class, 'storeCustomerInfo']);
        
        Route::get('/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
        Route::post('/payment', [CheckoutController::class, 'processPayment']);
        
        Route::get('/confirmation/{booking}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    });
    
    // Customer Account
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'dashboard'])->name('account.dashboard');
        
        // Bookings
        Route::get('/bookings', [AccountController::class, 'bookings'])->name('account.bookings');
        Route::get('/bookings/{booking}', [AccountController::class, 'bookingDetails'])->name('account.booking.details');
        Route::get('/bookings/{booking}/invoice', [AccountController::class, 'downloadInvoice'])->name('account.booking.invoice');
        
        // Profile
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
        
        // Password
        Route::get('/password', [AccountController::class, 'password'])->name('account.password');
        Route::put('/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    });
});

/*
|--------------------------------------------------------------------------
| API Routes for AJAX
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    // Check availability
    Route::post('/check-availability', [ProductController::class, 'checkAvailability'])->name('api.check-availability');
    
    // Get product variations
    Route::get('/products/{product}/variations', [ProductController::class, 'getVariations'])->name('api.product.variations');
    
    // Calculate price
    Route::post('/calculate-price', [ProductController::class, 'calculatePrice'])->name('api.calculate-price');
});

// Fallback route (404)
Route::fallback(function () {
    return view('errors.404');
});