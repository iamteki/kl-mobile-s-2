<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PriceDisplay extends Component
{
    public $amount;
    public $currency;
    public $period;
    
    public function __construct($amount, $currency = 'LKR', $period = null)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->period = $period;
    }
    
    public function render()
    {
        return view('components.price-display');
    }
}