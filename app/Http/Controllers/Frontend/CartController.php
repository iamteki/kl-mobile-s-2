<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;
    
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    public function index()
    {
        $cart = $this->cartService->getCart();
        
        return view('frontend.cart.index', compact('cart'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variation_id' => 'nullable|exists:product_variations,id',
            'event_date' => 'nullable|date|after:today'
        ]);
        
        $this->cartService->addItem(
            $request->product_id,
            $request->quantity,
            $request->variation_id,
            $request->event_date
        );
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cart' => $this->cartService->getCart()
            ]);
        }
        
        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }
    
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $this->cartService->updateQuantity($itemId, $request->quantity);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'cart' => $this->cartService->getCart()
            ]);
        }
        
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
    
    public function remove($itemId)
    {
        $this->cartService->removeItem($itemId);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart' => $this->cartService->getCart()
            ]);
        }
        
        return redirect()->back()->with('success', 'Item removed from cart!');
    }
    
    public function clear()
    {
        $this->cartService->clearCart();
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }
}