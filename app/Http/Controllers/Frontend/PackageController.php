<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Services\CartService;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display all packages
     */
    public function index()
    {
        $packages = Package::where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('featured', 'desc')
            ->get()
            ->map(function ($package) {
                // Ensure features and items are arrays
                if (is_string($package->features)) {
                    $package->features = json_decode($package->features, true) ?? [];
                }
                if (is_string($package->items)) {
                    $package->items = json_decode($package->items, true) ?? [];
                }
                return $package;
            });
        
        return view('frontend.packages.index', compact('packages'));
    }
    
    /**
     * Display package details
     */
    public function show($packageSlug)
    {
        $package = Package::where('slug', $packageSlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Ensure features and items are arrays
        if (is_string($package->features)) {
            $package->features = json_decode($package->features, true) ?? [];
        }
        if (is_string($package->items)) {
            $package->items = json_decode($package->items, true) ?? [];
        }
        
        // Get related packages from same category
        $relatedPackages = Package::where('id', '!=', $package->id)
            ->where('category', $package->category)
            ->where('status', 'active')
            ->limit(3)
            ->get()
            ->map(function ($relatedPackage) {
                if (is_string($relatedPackage->features)) {
                    $relatedPackage->features = json_decode($relatedPackage->features, true) ?? [];
                }
                return $relatedPackage;
            });
        
        // If not enough related packages from same category, get from other categories
        if ($relatedPackages->count() < 3) {
            $additionalPackages = Package::where('id', '!=', $package->id)
                ->whereNotIn('id', $relatedPackages->pluck('id'))
                ->where('status', 'active')
                ->limit(3 - $relatedPackages->count())
                ->get()
                ->map(function ($additionalPackage) {
                    if (is_string($additionalPackage->features)) {
                        $additionalPackage->features = json_decode($additionalPackage->features, true) ?? [];
                    }
                    return $additionalPackage;
                });
            
            $relatedPackages = $relatedPackages->concat($additionalPackages);
        }
        
        return view('frontend.packages.show', compact('package', 'relatedPackages'));
    }

    /**
     * Add package to cart (AJAX)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'event_date' => 'nullable|date|after:today',
            'event_type' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string|max:500',
            'attendees' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000'
        ]);

        $package = Package::findOrFail($request->package_id);

        // Check if package is active
        if ($package->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This package is currently unavailable.'
            ], 400);
        }

        // Prepare cart item data
        $itemData = [
            'type' => 'package',
            'id' => $package->id,
            'name' => $package->name,
            'price' => $package->price,
            'quantity' => 1,
            'image' => $package->image,
            'attributes' => array_filter([
                'category' => $package->category,
                'suitable_for' => $package->suitable_for,
                'service_duration' => $package->service_duration,
                'event_date' => $request->event_date,
                'event_type' => $request->event_type,
                'venue_address' => $request->venue_address,
                'attendees' => $request->attendees,
                'notes' => $request->notes
            ])
        ];

        // Add to cart
        $this->cartService->add($itemData);

        return response()->json([
            'success' => true,
            'message' => 'Package added to cart successfully!',
            'cartCount' => $this->cartService->getCount(),
            'cartTotal' => $this->cartService->getSummary()['total']
        ]);
    }

    /**
     * Quick view package details (AJAX)
     */
    public function quickView($packageId)
    {
        $package = Package::where('id', $packageId)
            ->where('status', 'active')
            ->firstOrFail();

        // Ensure features and items are arrays
        if (is_string($package->features)) {
            $package->features = json_decode($package->features, true) ?? [];
        }
        if (is_string($package->items)) {
            $package->items = json_decode($package->items, true) ?? [];
        }

        return response()->json([
            'success' => true,
            'html' => view('frontend.packages.partials.quick-view', compact('package'))->render()
        ]);
    }
}