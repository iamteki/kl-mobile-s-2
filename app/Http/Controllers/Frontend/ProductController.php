<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    protected $availabilityService;
    protected $pricingService;
    
    public function __construct(
        AvailabilityService $availabilityService,
        PricingService $pricingService
    ) {
        $this->availabilityService = $availabilityService;
        $this->pricingService = $pricingService;
    }
    
    public function show($categorySlug, $productSlug)
    {
        // Get category
        $category = Category::where('slug', $categorySlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Get product with relationships
        $product = Product::with([
            'category',
            'variations',
            'media',
            'attributes'
        ])
            ->where('slug', $productSlug)
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Transform product data
        $product->main_image_url = $product->getFirstMediaUrl('main');
        $product->gallery_images = $product->getMedia('gallery')->map(function ($media) {
            return [
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb')
            ];
        });
        
        // Get specifications based on category
        $specifications = $this->getProductSpecifications($product);
        
        // Get features
        $features = $this->getProductFeatures($product);
        
        // Get what's included
       $included = $product->included_items ?: $this->getDefaultIncluded($product);
        
        // Get requirements
        $requirements = $this->getProductRequirements($product);
        
        // Get related products
        $relatedProducts = Product::with(['category', 'media'])
            ->where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(function ($relatedProduct) {
                $relatedProduct->main_image_url = $relatedProduct->getFirstMediaUrl('main');
                $relatedProduct->availability_class = $this->getAvailabilityClass($relatedProduct->available_quantity);
                return $relatedProduct;
            });
        
        // Get pricing tiers
        $pricingTiers = $this->pricingService->getPricingTiers($product);
        
        // Get availability calendar data
        $calendarData = $this->availabilityService->getCalendarData($product->id, Carbon::now(), Carbon::now()->addMonths(2));
        
        return view('frontend.products.show', compact(
            'category',
            'product',
            'specifications',
            'features',
            'included',
            'requirements',
            'relatedProducts',
            'pricingTiers',
            'calendarData'
        ));
    }
    
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $available = $this->availabilityService->checkAvailability(
            $request->product_id,
            $request->variation_id,
            $request->start_date,
            $request->end_date,
            $request->quantity
        );
        
        return response()->json([
            'available' => $available,
            'message' => $available 
                ? 'Equipment is available for your dates!' 
                : 'Sorry, this equipment is not available for the selected dates.'
        ]);
    }
    
    public function getVariations(Product $product)
    {
        $product->load('variations');
        
        return response()->json([
            'variations' => $product->variations->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'name' => $variation->name,
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'price_formatted' => 'LKR ' . number_format($variation->price),
                    'available' => $variation->available_quantity > 0,
                    'quantity' => $variation->available_quantity,
                    'attributes' => $variation->attributes
                ];
            })
        ]);
    }
    
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        $pricing = $this->pricingService->calculatePrice(
            $product,
            $request->start_date,
            $request->end_date,
            $request->quantity,
            $request->variation_id
        );
        
        return response()->json($pricing);
    }
    
    private function getProductSpecifications($product)
    {
        $specs = [];
        
        // Get attributes and format as specifications
        foreach ($product->attributes as $attribute) {
            $specs[] = [
                'label' => $attribute->attribute_name,
                'value' => $attribute->attribute_value,
                'icon' => $this->getSpecIcon($attribute->attribute_name)
            ];
        }
        
        // Add default specifications based on category
        switch($product->category->slug) {
            case 'sound-systems':
                $defaultSpecs = [
                    ['label' => 'Power Output', 'value' => '1000W RMS', 'icon' => 'fas fa-bolt'],
                    ['label' => 'Coverage Area', 'value' => 'Up to 200 guests', 'icon' => 'fas fa-expand'],
                    ['label' => 'Setup Time', 'value' => '30-45 minutes', 'icon' => 'fas fa-clock']
                ];
                break;
                
            case 'lighting':
                $defaultSpecs = [
                    ['label' => 'Light Type', 'value' => 'LED', 'icon' => 'fas fa-lightbulb'],
                    ['label' => 'Color Options', 'value' => 'RGB + White', 'icon' => 'fas fa-palette'],
                    ['label' => 'DMX Control', 'value' => 'Yes', 'icon' => 'fas fa-sliders-h']
                ];
                break;
                
            case 'led-screens':
                $defaultSpecs = [
                    ['label' => 'Resolution', 'value' => 'Full HD', 'icon' => 'fas fa-tv'],
                    ['label' => 'Brightness', 'value' => '5000 nits', 'icon' => 'fas fa-sun'],
                    ['label' => 'Viewing Angle', 'value' => '140°', 'icon' => 'fas fa-eye']
                ];
                break;
                
            default:
                $defaultSpecs = [];
        }
        
        // Merge with defaults if specs are empty
        if (empty($specs) && !empty($defaultSpecs)) {
            $specs = $defaultSpecs;
        }
        
        return $specs;
    }
    
    private function getProductFeatures($product)
    {
        // Try to get features from product data
        if ($product->features) {
            return json_decode($product->features, true) ?? [];
        }
        
        // Default features based on category
        $defaultFeatures = [
            'sound-systems' => [
                'Professional grade audio quality',
                'Easy setup and operation',
                'Includes all necessary cables',
                'Technical support available',
                'Suitable for indoor and outdoor events',
                'Built-in protection circuits'
            ],
            'lighting' => [
                'Energy efficient LED technology',
                'Multiple color options',
                'DMX compatible',
                'Safe for indoor/outdoor use',
                'Professional mounting hardware included',
                'Remote control available'
            ],
            'led-screens' => [
                'High resolution display',
                'Wide viewing angle',
                'Weather resistant',
                'Content management system included',
                'Multiple input sources supported',
                'Professional setup service'
            ],
            'staging' => [
                'Modular design for flexible configurations',
                'Non-slip surface',
                'Professional grade construction',
                'Safety railings included',
                'Quick assembly system',
                'Certified load capacity'
            ],
            'generators' => [
                'Silent operation technology',
                'Fuel efficient',
                'Automatic voltage regulation',
                'Multiple power outlets',
                'Weather protected',
                '24/7 technical support'
            ]
        ];
        
        return $defaultFeatures[$product->category->slug] ?? [
            'Professional quality equipment',
            'Well maintained and tested',
            'Delivery and pickup available',
            'Technical support included'
        ];
    }
    
    private function getDefaultIncluded($product)
    {
        $defaultIncluded = [
            'sound-systems' => [
                'Main speakers and amplifiers',
                'All necessary cables and connectors',
                'Basic mixing console',
                'Microphone (if applicable)',
                'Speaker stands',
                'Setup instructions',
                'Power distribution',
                'Transport covers'
            ],
            'lighting' => [
                'Light fixtures as described',
                'Power cables',
                'DMX cables',
                'Controller (if applicable)',
                'Safety cables',
                'Mounting clamps',
                'Color gels (if applicable)',
                'Transport cases'
            ],
            'led-screens' => [
                'LED panels',
                'Processing unit',
                'All cables and connectors',
                'Mounting structure',
                'Basic content package',
                'Remote control',
                'Setup tools',
                'Weather protection covers'
            ],
            'staging' => [
                'Stage platforms',
                'Support legs',
                'Safety railings',
                'Steps/stairs',
                'Joining clips',
                'Leveling feet',
                'Non-slip surfaces',
                'Assembly tools'
            ]
        ];
        
        return $defaultIncluded[$product->category->slug] ?? [
            'Main equipment',
            'All necessary cables',
            'Setup guide',
            'Basic accessories'
        ];
    }
    
    private function getProductRequirements($product)
    {
        if ($product->requirements) {
            return json_decode($product->requirements, true) ?? [];
        }
        
        $defaultRequirements = [
            'sound-systems' => [
                'Stable power supply (230V)',
                'Adequate space for setup (minimum 3m x 2m)',
                'Protection from weather if outdoor event',
                'Access to venue 2 hours before event',
                'Level ground for speaker placement',
                'Clear line of sight to audience area'
            ],
            'lighting' => [
                'Adequate power points (16A recommended)',
                'Ceiling height minimum 3m',
                'Stable mounting points if rigging',
                'Clear access paths for setup',
                'Protection from rain if outdoor',
                'Darkened environment for best effect'
            ],
            'led-screens' => [
                'Level ground surface',
                'Power supply within 50m',
                'Protection from direct sunlight',
                'Minimum 2m clearance in front',
                'Vehicle access for delivery',
                'Secure area to prevent tampering'
            ],
            'staging' => [
                'Level ground surface',
                'Adequate space for assembly',
                'Vehicle access for delivery',
                'Weather protection if outdoor',
                'Safety barriers if required',
                'Loading capacity not to be exceeded'
            ],
            'generators' => [
                'Well-ventilated area',
                'Level ground placement',
                'Minimum 3m from buildings',
                'Security fencing if required',
                'Fuel storage area',
                'Fire extinguisher on site'
            ]
        ];
        
        return $defaultRequirements[$product->category->slug] ?? [
            'Adequate power supply',
            'Sufficient space for setup',
            'Access to venue before event',
            'Weather protection if needed'
        ];
    }
    
    private function getSpecIcon($attributeName)
    {
        $iconMap = [
            'Power Output' => 'fas fa-bolt',
            'Coverage Area' => 'fas fa-expand',
            'Weight' => 'fas fa-weight',
            'Dimensions' => 'fas fa-ruler-combined',
            'Frequency Response' => 'fas fa-wave-square',
            'Resolution' => 'fas fa-tv',
            'Brightness' => 'fas fa-sun',
            'Color' => 'fas fa-palette',
            'Channels' => 'fas fa-sliders-h',
            'Wattage' => 'fas fa-plug',
            'Capacity' => 'fas fa-users',
            'Setup Time' => 'fas fa-clock',
            'Brand' => 'fas fa-tag',
            'Model' => 'fas fa-hashtag',
            'Input Types' => 'fas fa-ethernet',
            'Output Types' => 'fas fa-sign-out-alt',
            'Connectivity' => 'fas fa-wifi',
            'Control' => 'fas fa-gamepad',
            'Safety' => 'fas fa-shield-alt',
            'Warranty' => 'fas fa-certificate'
        ];
        
        return $iconMap[$attributeName] ?? 'fas fa-check-circle';
    }
    
    private function getAvailabilityClass($quantity)
    {
        if ($quantity <= 0) {
            return 'out-of-stock';
        } elseif ($quantity <= 3) {
            return 'low-stock';
        } else {
            return 'in-stock';
        }
    }
    
    /**
     * Get attribute value from product with fallback
     */
    private function getAttribute($product, $key, $default = null)
    {
        $attribute = $product->attributes->firstWhere('attribute_name', $key);
        return $attribute ? $attribute->attribute_value : $default;
    }
}