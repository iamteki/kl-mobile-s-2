<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($package) {
                $package->features = json_decode($package->features, true) ?? [];
                return $package;
            });
        
        return view('frontend.packages.index', compact('packages'));
    }
    
    public function show($packageSlug)
    {
        $package = Package::where('slug', $packageSlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        $package->features = json_decode($package->features, true) ?? [];
        $package->items = json_decode($package->items, true) ?? [];
        
        // Get related packages
        $relatedPackages = Package::where('id', '!=', $package->id)
            ->where('status', 'active')
            ->limit(3)
            ->get();
        
        return view('frontend.packages.show', compact('package', 'relatedPackages'));
    }
}