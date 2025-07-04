<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the customer profile associated with the user.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get all bookings for the user through the customer relationship.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'id');
    }

    /**
     * Check if user has a customer profile
     */
    public function hasCustomerProfile(): bool
    {
        return $this->customer()->exists();
    }

    /**
     * Get the user's full address from customer profile
     */
    public function getFullAddressAttribute()
    {
        return $this->customer?->address;
    }

    /**
     * Get the user's phone from customer profile
     */
    public function getPhoneAttribute()
    {
        return $this->customer?->phone;
    }

    /**
     * Check if user is a corporate customer
     */
    public function isCorporate(): bool
    {
        return $this->customer?->customer_type === 'corporate';
    }

    /**
     * Get the user's company name if corporate
     */
    public function getCompanyAttribute()
    {
        return $this->customer?->company;
    }
}