<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Contact Information
    |--------------------------------------------------------------------------
    |
    | This information is used throughout the site for contact details
    |
    */
    'contact' => [
        'phone' => env('SITE_PHONE', '+94 77 123 4567'),
        'email' => env('SITE_EMAIL', 'info@klmobile.com'),
        'hours' => env('SITE_HOURS', 'Mon-Sat: 9:00 AM - 6:00 PM'),
        'address' => env('SITE_ADDRESS', 'Colombo, Sri Lanka'),
        'whatsapp' => env('SITE_WHATSAPP', '+94771234567'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Links
    |--------------------------------------------------------------------------
    */
    'social' => [
        'facebook' => env('SOCIAL_FACEBOOK', 'https://facebook.com/klmobile'),
        'instagram' => env('SOCIAL_INSTAGRAM', 'https://instagram.com/klmobile'),
        'youtube' => env('SOCIAL_YOUTUBE', null),
        'linkedin' => env('SOCIAL_LINKEDIN', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    */
    'company' => [
        'name' => env('APP_NAME', 'KL Mobile DJ & Events'),
        'tagline' => 'Your Complete Event Solution',
        'description' => 'Professional event equipment rental and services in Sri Lanka',
        'founded' => '2010',
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Settings
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'default_title' => env('APP_NAME', 'KL Mobile') . ' - Event Equipment Rental & DJ Services',
        'default_description' => 'Rent professional event equipment, sound systems, lighting, and book DJ services. Instant booking with real-time availability in Sri Lanka.',
        'default_keywords' => 'event equipment rental, dj services, sound system rental, lighting rental, sri lanka events',
        'og_image' => '/images/og-default.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Settings
    |--------------------------------------------------------------------------
    */
    'business' => [
        'min_rental_days' => 1,
        'advance_booking_days' => 1,
        'max_booking_days' => 365,
        'currency' => 'LKR',
        'tax_rate' => 0, // percentage
        'deposit_percentage' => 50,
    ],
];