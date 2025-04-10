<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->with('orderItems.product')
                      ->orderBy('order_date', 'desc')
                      ->get();
        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())
                     ->with(['orderItems.product', 'payment'])
                     ->findOrFail($id);
        return view('customer.orders.show', compact('order'));
    }

    public function checkout()
    {
        $cart = Cart::where('user_id', auth()->id())
                   ->where('status', 'active')
                   ->with('cartItems.product')
                   ->firstOrFail();
                   
        if ($cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty');
        }
        
        return view('customer.orders.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string'
        ]);

        $cart = Cart::where('user_id', auth()->id())
                   ->where('status', 'active')
                   ->with('cartItems.product')
                   ->firstOrFail();
        
        if ($cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty');
        }
        
        // Calculate total amount
        $totalAmount = 0;
        foreach ($cart->cartItems as $item) {
            $totalAmount += $item->quantity * $item->unit_price;
        }
        
        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'cart_id' => $cart->cart_id,
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'status' => 'processing'
        ]);
        
        // Create order items
        foreach ($cart->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price
            ]);
        }
        
        // Mark cart as completed
        $cart->update(['status' => 'completed']);
        
        return redirect()->route('customer.orders.show', $order->order_id)
                        ->with('success', 'Order placed successfully');
    }
}