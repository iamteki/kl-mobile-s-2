<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Category;
use App\Models\Service;
use App\Models\Package;

class NavigationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get active categories for equipment dropdown
        $equipmentCategories = Category::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->select('name', 'slug', 'icon')
            ->get();
        
        // Get service categories with count
        $serviceCategories = Service::active()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category,
                    'slug' => strtolower(str_replace(' ', '-', $item->category)),
                    'count' => $item->count
                ];
            });
        
        // Get featured services for each category (optional - for mega menu)
        $servicesByCategory = Service::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
        
        // Check if there are any active packages
        $hasPackages = Package::active()->exists();
        
        $view->with([
            'navEquipmentCategories' => $equipmentCategories,
            'navServiceCategories' => $serviceCategories,
            'navServicesByCategory' => $servicesByCategory,
            'navHasPackages' => $hasPackages
        ]);
    }
}