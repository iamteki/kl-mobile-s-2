<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Get services from database
        $query = Service::active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');
        
        // Filter by category if requested
        $category = $request->get('category');
        if ($category && $category !== 'all') {
            $categoryMap = [
                'entertainment' => 'Entertainment',
                'technical-crew' => 'Technical Crew',
                'media-production' => 'Media Production',
                'event-staff' => 'Event Staff'
            ];
            
            if (isset($categoryMap[$category])) {
                $query->where('category', $categoryMap[$category]);
            }
        }
        
        // Filter by experience level
        $experience = $request->get('experience');
        if ($experience) {
            $query->where('experience_level', $experience);
        }
        
        // Get services
        $services = $query->get();
        
        // Get category counts for tabs
        $categoryCounts = Service::active()
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');
        
        return view('frontend.services.index', compact('services', 'categoryCounts'));
    }
    
    public function show($serviceSlug)
    {
        $service = Service::where('slug', $serviceSlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Get related services
        $relatedServices = Service::where('category', $service->category)
            ->where('id', '!=', $service->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
        
        return view('frontend.services.show', compact('service', 'relatedServices'));
    }
}