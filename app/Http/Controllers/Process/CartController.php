<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add a product to the cart
     */
    public function addToCart(Request $request, $productId)
    {
        // Validate request
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Get the product
        $product = Product::findOrFail($productId);
        
        // Check if product is in stock
        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Get or create active cart for the user
        $cart = Cart::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status' => 'active'
            ]
        );

        // Check if product already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            // Update quantity if product already in cart
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Add new cart item
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'added_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request, $cartItemId)
    {
        // Validate request
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Get cart item
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check if cart belongs to user
        $cart = Cart::where('cart_id', $cartItem->cart_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // Check product stock
        $product = Product::findOrFail($cartItem->product_id);
        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Update quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('customer.cart')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function removeCartItem($cartItemId)
    {
        // Get cart item
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check if cart belongs to user
        $cart = Cart::where('cart_id', $cartItem->cart_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // Delete cart item
        $cartItem->delete();

        return redirect()->route('customer.cart')->with('success', 'Item removed from cart.');
    }

    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        // Get active cart
        $cart = Cart::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->first();

        if ($cart) {
            // Delete all cart items
            CartItem::where('cart_id', $cart->cart_id)->delete();
        }

        return redirect()->route('customer.cart')->with('success', 'Cart cleared successfully.');
    }
    
    /**
     * Increment cart item quantity by 1
     */
    public function incrementCartItem($cartItemId)
    {
        // Get cart item
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check if cart belongs to user
        $cart = Cart::where('cart_id', $cartItem->cart_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
                    
        // Check product stock
        $product = Product::findOrFail($cartItem->product_id);
        if ($product->stock_quantity <= $cartItem->quantity) {
            return redirect()->back()->with('error', 'Maximum stock reached.');
        }
        
        // Increment quantity
        $cartItem->quantity += 1;
        $cartItem->save();
        
        return redirect()->route('customer.cart')->with('success', 'Quantity updated.');
    }
    
    /**
     * Decrement cart item quantity by 1
     */
    public function decrementCartItem($cartItemId)
    {
        // Get cart item
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check if cart belongs to user
        $cart = Cart::where('cart_id', $cartItem->cart_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
        
        // Check if quantity is already 1
        if ($cartItem->quantity <= 1) {
            return redirect()->back()->with('error', 'Minimum quantity reached.');
        }
        
        // Decrement quantity
        $cartItem->quantity -= 1;
        $cartItem->save();
        
        return redirect()->route('customer.cart')->with('success', 'Quantity updated.');
    }
}