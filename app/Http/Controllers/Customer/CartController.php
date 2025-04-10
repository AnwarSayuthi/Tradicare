<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
                   ->where('status', 'active')
                   ->with('cartItems.product')
                   ->first();
                   
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'status' => 'active'
            ]);
        }
        
        return view('customer.cart.index', compact('cart'));
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Get or create active cart
        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'active'],
            ['user_id' => auth()->id(), 'status' => 'active']
        );

        // Check if product already in cart
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
                           ->where('product_id', $request->product_id)
                           ->first();

        $product = Product::findOrFail($request->product_id);

        if ($cartItem) {
            // Update quantity if already exists
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
                'unit_price' => $product->price
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'added_at' => now()
            ]);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Product added to cart');
    }

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        // Ensure user owns this cart item
        $cart = Cart::where('user_id', auth()->id())
                   ->where('cart_id', $cartItem->cart_id)
                   ->firstOrFail();
        
        $cartItem->update(['quantity' => $request->quantity]);
        
        return redirect()->route('customer.cart.index')->with('success', 'Cart updated');
    }

    public function removeItem($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Ensure user owns this cart item
        $cart = Cart::where('user_id', auth()->id())
                   ->where('cart_id', $cartItem->cart_id)
                   ->firstOrFail();
        
        $cartItem->delete();
        
        return redirect()->route('customer.cart.index')->with('success', 'Item removed from cart');
    }
}