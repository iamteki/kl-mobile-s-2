<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'is_admin', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', 
        ];
    }

    // Add this method for Filament access
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin === true;
    }

    // Your existing relationships remain the same
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'id');
    }

    public function hasCustomerProfile(): bool
    {
        return $this->customer()->exists();
    }

    public function getFullAddressAttribute()
    {
        return $this->customer?->address;
    }

    public function getPhoneAttribute()
    {
        return $this->customer?->phone;
    }

    public function isCorporate(): bool
    {
        return $this->customer?->customer_type === 'corporate';
    }

    public function getCompanyAttribute()
    {
        return $this->customer?->company;
    }
}