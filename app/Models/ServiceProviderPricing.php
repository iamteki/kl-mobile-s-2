<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProviderPricing extends Model
{
    use HasFactory;

    protected $table = 'service_provider_pricing';

    protected $fillable = [
        'service_provider_id',
        'tier_name',
        'price',
        'duration',
        'included_features',
        'additional_costs',
        'sort_order',
        'is_popular'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'included_features' => 'array',
        'additional_costs' => 'array',
        'sort_order' => 'integer',
        'is_popular' => 'boolean'
    ];

    /**
     * Get the service provider
     */
    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}