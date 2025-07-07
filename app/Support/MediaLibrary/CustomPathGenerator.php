<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $model = $media->model;
        
        // Check if this is a Product model
        if ($model instanceof \App\Models\Product) {
            // Create a folder structure like: products/{product-id}/{collection-name}
            return 'products/' . $model->id . '/' . $media->collection_name;
        }
        
        // Check if this is a ServiceProvider model
        if ($model instanceof \App\Models\ServiceProvider) {
            // Create a folder structure like: service-providers/{provider-id}/{collection-name}
            return 'service-providers/' . $model->id . '/' . $media->collection_name;
        }
        
        // Check if this is a Package model
        if ($model instanceof \App\Models\Package) {
            // Create a folder structure like: packages/{package-id}/{collection-name}
            return 'packages/' . $model->id . '/' . $media->collection_name;
        }
        
        // Default fallback - use the default Spatie structure
        return $media->id;
    }
}