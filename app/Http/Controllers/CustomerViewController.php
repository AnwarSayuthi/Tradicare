<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class CustomerViewController extends Controller
{
    public function landing()
    {
        $services = Service::where('active', 1)
            ->select('service_id', 'service_name', 'description', 'price')
            ->get();
            
        $products = Product::where('active', 1)
            ->select('product_id', 'product_name', 'price', 'product_image')
            ->limit(4)
            ->get();

        return view('landing', compact('services', 'products'));
    }

    public function products()
    {
        $products = Product::where('active', true)
                      ->orderBy('product_name')
                      ->paginate(12);
                      
        // Get unique categories for filter
        $categories = Product::where('active', true)
                        ->distinct()
                        ->pluck('category')
                        ->toArray();
                        
        return view('customer.products.index', compact('products', 'categories'));
    }
    
    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Get related products in the same category
        $relatedProducts = Product::where('category', $product->category)
            ->where('product_id', '!=', $product->product_id)
            ->where('active', true)
            ->limit(4)
            ->get();
            
        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
    
    // The error is in the addToCart method. Here's the fix:
    public function addToCart(Request $request, $productId)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Get the product
        $product = Product::findOrFail($productId);
        
        // Check if product is in stock
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Sorry, we don\'t have enough stock for this product.');
        }
        
        // Get or create active cart for the user
        $cart = Cart::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'status' => 'active'
            ]
        );
        
        // Check if product already in cart items
        $existingCartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $productId)
            ->first();
        
        if ($existingCartItem) {
            // Update quantity if product already in cart
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();
        } else {
            // Add new item to cart
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

    public function appointments()
    {
        $appointments = Appointment::with('service')
            ->where('user_id', auth()->id())
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('customer.appointments.index', compact('appointments'));
    }

    public function appointmentCreate()
    {
        $services = Service::where('active', 1)->get();
        return view('customer.appointments.create', compact('services'));
    }

    // Update the cart method to use the new cart system
    public function cart()
    {
        // Get the active cart for the current user
        $cart = Cart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->with('cartItems.product')
                    ->first();
        
        // If no active cart exists, create one
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'status' => 'active'
            ]);
        }
        
        // Calculate total price
        $totalPrice = 0;
        if ($cart) {
            foreach ($cart->cartItems as $item) {
                $totalPrice += $item->unit_price * $item->quantity;
            }
        }
        
        return view('customer.cart.index', compact('cart', 'totalPrice'));
    }

    // Remove the addToCart method as it's now handled by the Process\CartController
    // public function addToCart(Request $request, $productId) { ... }

    // Remove the removeFromCart method as it's now handled by the Process\CartController
    // public function removeFromCart($itemId) { ... }

    public function checkout()
    {
        // Get the active cart for the current user
        $cart = Cart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
        }
        
        // Get cart items with product details
        $cartItems = CartItem::with('product')
                            ->where('cart_id', $cart->cart_id)
                            ->get();
        
        // Calculate total price
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->unit_price * $item->quantity;
        }
        
        return view('customer.cart.checkout', compact('cart', 'cartItems', 'totalPrice'));
    }

    public function placeOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);
        
        // Get the active cart
        $cart = Cart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
        }
        
        // Calculate total amount
        $totalAmount = 0;
        foreach ($cart->cartItems as $item) {
            $totalAmount += $item->unit_price * $item->quantity;
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
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'subtotal' => $cartItem->unit_price * $cartItem->quantity
            ]);
            
            // Update product stock
            $product = Product::find($cartItem->product_id);
            $product->stock_quantity -= $cartItem->quantity;
            $product->save();
        }
        
        // Create payment record
        Payment::create([
            'user_id' => auth()->id(),
            'order_id' => $order->order_id,
            'amount' => $totalAmount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'paid',
            'transaction_id' => 'TXN' . time() . rand(1000, 9999)
        ]);
        
        // Mark cart as completed
        $cart->status = 'completed';
        $cart->save();
        
        return redirect()->route('customer.orders')->with('success', 'Order placed successfully!');
    }

    public function orders()
    {
        $orders = Order::with(['orderItems.product', 'payment'])
                      ->where('user_id', auth()->id())
                      ->orderBy('order_date', 'desc')
                      ->paginate(10);
        
        return view('customer.orders.index', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Order::with(['orderItems.product', 'payment'])
                     ->where('user_id', auth()->id())
                     ->where('order_id', $id)
                     ->firstOrFail();
        
        return view('customer.orders.show', compact('order'));
    }

    // Add this method to your CustomerViewController class
    public function profile()
    {
        $user = auth()->user();
        $locations = $user->locations()->get();
        
        return view('customer.profile.index', compact('user', 'locations'));
    }

    // Add this method to handle adding new addresses
    public function addLocation(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
        ]);
        
        $user = auth()->user();
        
        // Check if this is the first address (make it default)
        $isDefault = $user->locations()->count() === 0;
        
        $location = new Location($request->all());
        $location->user_id = $user->id;
        $location->is_default = $request->has('is_default') ? true : $isDefault;
        $location->is_pickup_address = $request->has('is_pickup_address');
        $location->is_return_address = $request->has('is_return_address');
        $location->save();
        
        // If this is set as default, remove default from other addresses
        if ($location->is_default) {
            $user->locations()
                ->where('location_id', '!=', $location->location_id)
                ->update(['is_default' => false]);
        }
        
        return redirect()->route('customer.profile')->with('success', 'Address added successfully!');
    }

    // Add method to update address
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
        ]);
        
        $location = Location::where('location_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $location->update($request->except(['is_default', 'is_pickup_address', 'is_return_address']));
        
        $location->is_default = $request->has('is_default');
        $location->is_pickup_address = $request->has('is_pickup_address');
        $location->is_return_address = $request->has('is_return_address');
        $location->save();
        
        // If this is set as default, remove default from other addresses
        if ($location->is_default) {
            auth()->user()->locations()
                ->where('location_id', '!=', $location->location_id)
                ->update(['is_default' => false]);
        }
        
        return redirect()->route('customer.profile')->with('success', 'Address updated successfully!');
    }

    // Add method to delete address
    public function deleteLocation($id)
    {
        $location = Location::where('location_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $wasDefault = $location->is_default;
        $location->delete();
        
        // If the deleted address was default, set another one as default
        if ($wasDefault) {
            $newDefault = auth()->user()->locations()->first();
            if ($newDefault) {
                $newDefault->is_default = true;
                $newDefault->save();
            }
        }
        
        return redirect()->route('customer.profile')->with('success', 'Address deleted successfully!');
    }
}