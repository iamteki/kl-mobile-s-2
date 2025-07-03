<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProviderMedia extends Model
{
    use HasFactory;

    protected $table = 'service_provider_media';

    protected $fillable = [
        'service_provider_id',
        'type',
        'url',
        'thumbnail_url',
        'title',
        'description',
        'sort_order',
        'is_featured'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_featured' => 'boolean'
    ];

    /**
     * Get the service provider
     */
    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * Scope for featured media
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get embed URL for videos
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->type !== 'video') {
            return null;
        }

        // Convert YouTube URL to embed format
        if (str_contains($this->url, 'youtube.com') || str_contains($this->url, 'youtu.be')) {
            $videoId = $this->extractYouTubeId($this->url);
            return "https://www.youtube.com/embed/{$videoId}";
        }

        // Return as-is for other video sources
        return $this->url;
    }

    /**
     * Extract YouTube video ID
     */
    private function extractYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? '';
    }
}