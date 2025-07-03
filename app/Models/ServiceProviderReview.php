<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ServiceProviderReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'customer_id',
        'booking_id',
        'rating',
        'title',
        'comment',
        'ratings_breakdown',
        'verified_booking',
        'is_featured',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'ratings_breakdown' => 'array',
        'verified_booking' => 'boolean',
        'is_featured' => 'boolean'
    ];

    protected $appends = [
        'formatted_rating',
        'star_rating_html'
    ];

    /**
     * Get the service provider
     */
    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for featured reviews
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get formatted rating
     */
    public function getFormattedRatingAttribute(): string
    {
        return number_format($this->rating, 1);
    }

    /**
     * Get star rating HTML
     */
    public function getStarRatingHtmlAttribute(): string
    {
        $html = '';
        $fullStars = floor($this->rating);
        $hasHalfStar = ($this->rating - $fullStars) >= 0.5;
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullStars) {
                $html .= "<i class='fas fa-star text-warning'></i>";
            } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                $html .= "<i class='fas fa-star-half-alt text-warning'></i>";
            } else {
                $html .= "<i class='far fa-star text-muted'></i>";
            }
        }
        
        return $html;
    }
}