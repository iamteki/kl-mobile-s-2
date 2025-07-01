<?php

namespace App\Livewire;

use Livewire\Component;

class VariationSelector extends Component
{
    public $product;
    public $selectedVariation = null;
    public $variations = [];
    
    protected $listeners = ['refreshVariations'];
    
    public function mount($product)
    {
        $this->product = $product;
        $this->loadVariations();
    }
    
    public function loadVariations()
    {
        $this->variations = $this->product->variations->map(function ($variation) {
            return [
                'id' => $variation->id,
                'name' => $variation->name,
                'sku' => $variation->sku,
                'price' => $variation->price,
                'price_formatted' => 'LKR ' . number_format($variation->price) . '/day',
                'available' => $variation->available_quantity > 0,
                'quantity' => $variation->available_quantity,
                'attributes' => $variation->attributes
            ];
        })->toArray();
    }
    
    public function selectVariation($variationId)
    {
        $this->selectedVariation = $variationId;
        
        // Emit event to update price calculator
        $variation = collect($this->variations)->firstWhere('id', $variationId);
      $this->dispatch('variationSelected', variation: $variation);
    }
    
    public function render()
    {
        return view('livewire.variation-selector');
    }
}