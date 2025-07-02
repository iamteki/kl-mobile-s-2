<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Cache contact info for 24 hours
        $contactInfo = Cache::remember('site_contact_info', 86400, function () {
            return [
                'phone' => config('site.contact.phone', '+94 77 123 4567'),
                'email' => config('site.contact.email', 'info@klmobile.com'),
                'hours' => config('site.contact.hours', 'Mon-Sat: 9:00 AM - 6:00 PM'),
                'address' => config('site.contact.address', 'Colombo, Sri Lanka'),
            ];
        });
        
        $view->with('contactInfo', $contactInfo);
    }
}