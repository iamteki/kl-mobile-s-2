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
        $included = json_decode($product->included_items, true) ?? $this->getDefaultIncluded($product);
        
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
    
    public function getVariations($productId)
    {
        $product = Product::with('variations')->findOrFail($productId);
        
        $variations = $product->variations->map(function ($variation) {
            return [
                'id' => $variation->id,
                'name' => $variation->name,
                'sku' => $variation->sku,
                'price' => $variation->price,
                'available_quantity' => $variation->available_quantity,
                'attributes' => $variation->attributes
            ];
        });
        
        return response()->json($variations);
    }
    
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer|min:1',
            'addons' => 'nullable|array'
        ]);
        
        $price = $this->pricingService->calculatePrice(
            $request->product_id,
            $request->variation_id,
            $request->start_date,
            $request->end_date,
            $request->quantity,
            $request->addons ?? []
        );
        
        return response()->json([
            'days' => $price['days'],
            'price_per_day' => $price['price_per_day'],
            'subtotal' => $price['subtotal'],
            'addons_total' => $price['addons_total'],
            'total' => $price['total'],
            'savings' => $price['savings'] ?? 0,
            'formatted_total' => 'LKR ' . number_format($price['total'])
        ]);
    }
    
    private function getProductSpecifications($product)
    {
        $specs = [];
        
        // Get specifications based on category
        switch($product->category->slug) {
            case 'sound-equipment':
                $specs = [
                    ['label' => 'Power Output (RMS)', 'value' => $product->getAttribute('power_output', '1000W')],
                    ['label' => 'Peak Power', 'value' => $product->getAttribute('peak_power', '2000W')],
                    ['label' => 'Frequency Response', 'value' => $product->getAttribute('frequency_response', '45Hz - 20kHz')],
                    ['label' => 'Coverage Area', 'value' => $product->getAttribute('coverage_area', 'Up to 200 pax indoor / 150 pax outdoor')],
                    ['label' => 'Speaker Configuration', 'value' => $product->getAttribute('speaker_config', '2x 15" Woofers + Horn Tweeters')],
                    ['label' => 'Input Types', 'value' => $product->getAttribute('input_types', 'XLR, 1/4" TRS, RCA')],
                    ['label' => 'Weight (per speaker)', 'value' => $product->getAttribute('weight', '45 kg')],
                    ['label' => 'Dimensions (per speaker)', 'value' => $product->getAttribute('dimensions', '700 x 450 x 400 mm')],
                    ['label' => 'Power Requirements', 'value' => $product->getAttribute('power_requirements', '220-240V AC, 50Hz')],
                    ['label' => 'Brand', 'value' => $product->brand],
                    ['label' => 'Model', 'value' => $product->getAttribute('model', 'PRX815W')]
                ];
                break;
                
            case 'lighting':
                $specs = [
                    ['label' => 'Light Type', 'value' => $product->getAttribute('light_type', 'LED Par')],
                    ['label' => 'Power Consumption', 'value' => $product->getAttribute('power_consumption', '150W')],
                    ['label' => 'Color Options', 'value' => $product->getAttribute('color_options', 'RGB + White')],
                    ['label' => 'DMX Channels', 'value' => $product->getAttribute('dmx_channels', '7 Channels')],
                    ['label' => 'Beam Angle', 'value' => $product->getAttribute('beam_angle', '25°')],
                    ['label' => 'Brightness', 'value' => $product->getAttribute('brightness', '10,000 Lumens')],
                    ['label' => 'Brand', 'value' => $product->brand],
                    ['label' => 'Model', 'value' => $product->getAttribute('model')]
                ];
                break;
                
            default:
                // Generic specifications
                $specs = [
                    ['label' => 'Brand', 'value' => $product->brand],
                    ['label' => 'Model', 'value' => $product->getAttribute('model')],
                    ['label' => 'Dimensions', 'value' => $product->getAttribute('dimensions')],
                    ['label' => 'Weight', 'value' => $product->getAttribute('weight')],
                ];
        }
        
        // Filter out empty values
        return array_filter($specs, function($spec) {
            return !empty($spec['value']);
        });
    }
    
    private function getProductFeatures($product)
    {
        // This would come from product attributes or features table
        $defaultFeatures = [
            'sound-equipment' => [
                ['icon' => 'fas fa-volume-up', 'title' => 'Crystal Clear Audio', 'description' => 'Professional-grade sound quality with minimal distortion even at high volumes.'],
                ['icon' => 'fas fa-wifi', 'title' => 'Wireless Ready', 'description' => 'Compatible with wireless microphone systems and Bluetooth audio streaming.'],
                ['icon' => 'fas fa-shield-alt', 'title' => 'Built-in Protection', 'description' => 'Thermal and overload protection ensures equipment safety during extended use.'],
                ['icon' => 'fas fa-cog', 'title' => 'Easy Setup', 'description' => 'Quick and simple setup with color-coded connections and clear labeling.'],
                ['icon' => 'fas fa-arrows-alt', 'title' => 'Versatile Mounting', 'description' => 'Can be pole-mounted, stacked, or used with professional speaker stands.'],
                ['icon' => 'fas fa-sliders-h', 'title' => 'DSP Control', 'description' => 'Digital Signal Processing for optimal sound tuning and feedback suppression.']
            ],
            'lighting' => [
                ['icon' => 'fas fa-palette', 'title' => 'Full Color Spectrum', 'description' => 'RGB color mixing with millions of color combinations.'],
                ['icon' => 'fas fa-wifi', 'title' => 'DMX Control', 'description' => 'Professional DMX512 control for synchronized lighting shows.'],
                ['icon' => 'fas fa-bolt', 'title' => 'Energy Efficient', 'description' => 'LED technology provides bright output with low power consumption.'],
                ['icon' => 'fas fa-sync', 'title' => 'Sound Activation', 'description' => 'Built-in programs can sync to music beats automatically.']
            ]
        ];
        
        return $defaultFeatures[$product->category->slug] ?? [];
    }
    
    private function getDefaultIncluded($product)
    {
        $defaults = [
            'sound-equipment' => [
                '2x Professional Speakers',
                '2x Professional Speaker Stands',
                '1x 8-Channel Mixing Console',
                '2x 50ft XLR Cables',
                '4x 25ft XLR Cables',
                'Power Distribution Unit',
                'All Necessary Power Cables',
                'Protective Covers During Transport'
            ],
            'lighting' => [
                'Complete Light Set as Described',
                'DMX Controller',
                'DMX Cables',
                'Power Cables',
                'Safety Cables',
                'Mounting Clamps',
                'Transport Cases'
            ]
        ];
        
        return $defaults[$product->category->slug] ?? ['Equipment as described', 'All necessary cables', 'Setup instructions'];
    }
    
    private function getProductRequirements($product)
    {
        return [
            'venue' => [
                'Power: 2x 13A power outlets within 10m of setup location',
                'Space: Minimum 3m x 2m for equipment placement',
                'Access: Ground floor or elevator access for equipment',
                'Security: Secure storage area if overnight setup'
            ],
            'rental' => [
                'Valid ID and deposit required',
                'Setup time: 1-2 hours before event',
                'Delivery: Free within KL city center',
                'Damage waiver available at checkout'
            ]
        ];
    }
    
    private function getAvailabilityClass($quantity)
    {
        if ($quantity <= 0) {
            return 'out-stock';
        } elseif ($quantity <= 3) {
            return 'low-stock';
        }
        
        return 'in-stock';
    }
}