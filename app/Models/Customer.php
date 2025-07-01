<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'company',
        'company_registration',
        'tax_id',
        'customer_type',
        'total_bookings',
        'total_spent',
        'last_booking_date',
        'preferences',
        'newsletter_subscribed',
        'sms_notifications'
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'preferences' => 'array',
        'newsletter_subscribed' => 'boolean',
        'sms_notifications' => 'boolean',
        'last_booking_date' => 'date',
        'total_bookings' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}