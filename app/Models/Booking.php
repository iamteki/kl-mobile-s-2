<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'event_date',
        'event_type',
        'venue',
        'number_of_pax',
        'installation_time',
        'event_start_time',
        'dismantle_time',
        'rental_start_date',
        'rental_end_date',
        'rental_days',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'tax_amount',
        'delivery_charge',
        'total',
        'payment_status',
        'payment_method',
        'stripe_payment_intent_id',
        'paid_at',
        'booking_status',
        'delivery_address',
        'delivery_instructions',
        'delivery_status',
        'special_requests',
        'internal_notes',
        'insurance_opted',
        'insurance_amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_company'
    ];

    protected $casts = [
        'event_date' => 'date',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'paid_at' => 'datetime',
        'installation_time' => 'datetime:H:i',
        'event_start_time' => 'datetime:H:i',
        'dismantle_time' => 'datetime:H:i',
        'number_of_pax' => 'integer',
        'rental_days' => 'integer',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'total' => 'decimal:2',
        'insurance_opted' => 'boolean',
        'insurance_amount' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('booking_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('booking_status', 'confirmed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }
}