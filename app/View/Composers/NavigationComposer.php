<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Category;
use App\Models\ServiceCategory;
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
        
        // Get service categories grouped by parent
        $serviceCategories = ServiceCategory::active()
            ->withCount('activeProviders')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('parent_category');
        
        // Parent categories for services
        $serviceParentCategories = [
            'entertainment' => [
                'name' => 'Entertainment',
                'icon' => 'fas fa-music'
            ],
            'technical-crew' => [
                'name' => 'Technical Crew',
                'icon' => 'fas fa-tools'
            ],
            'media-production' => [
                'name' => 'Media Production',
                'icon' => 'fas fa-camera'
            ],
            'event-staff' => [
                'name' => 'Event Staff',
                'icon' => 'fas fa-users'
            ]
        ];
        
        // Check if there are any active packages
        $hasPackages = Package::active()->exists();
        
        $view->with([
            'navEquipmentCategories' => $equipmentCategories,
            'navServiceCategories' => $serviceCategories,
            'navServiceParentCategories' => $serviceParentCategories,
            'navHasPackages' => $hasPackages
        ]);
    }
}