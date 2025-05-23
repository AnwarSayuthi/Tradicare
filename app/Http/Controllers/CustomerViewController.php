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
use Carbon\Carbon;

class CustomerViewController extends Controller
{
    public function landing()
    {
        $services = Service::notDeleted()
            ->where('active', 1)
            ->select('service_id', 'service_name', 'description', 'price')
            ->get();
        
        $products = Product::where('active', 1)
            ->select('product_id', 'product_name', 'price', 'product_image')
            ->limit(4)
            ->get();

        return view('landing', compact('services', 'products'));
    }

    public function products(Request $request)
    {
        $query = Product::where('active', true);
        
        // Apply search if provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter if provided
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        // Apply sorting
        $sortBy = $request->get('sort', 'name_asc');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('product_name', 'asc');
                break;
        }
        
        $products = $query->paginate(12);
        
        // Get categories with product counts
        $categories = Product::getActiveCategories();
        
        return view('customer.products.index', compact('products', 'categories', 'sortBy'));
    }

    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Check if product is active, if not redirect to products page
        if (!$product->active) {
            return redirect()->route('customer.products.index')
                ->with('error', 'This product is currently unavailable.');
        }
        
        // Get related products using the model method
        $relatedProducts = $product->getRelatedProducts(4);
        
        // Get categories for sidebar
        $categories = Product::getActiveCategories();
        
        return view('customer.products.show', compact('product', 'relatedProducts', 'categories'));
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

    /**
     * Display the appointment creation form
     *
     * @return \Illuminate\View\View
     */

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

    // Add this to the checkout method in CustomerViewController.php
    // Update the checkout method to include address selection
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
        
        // Get the user's default address or first address
        $user = auth()->user();
        $selectedAddress = $user->locations()->where('is_default', true)->first();
        
        // If no default address, get the first address
        if (!$selectedAddress && $user->locations()->count() > 0) {
            $selectedAddress = $user->locations()->first();
        }
        
        return view('customer.cart.checkout', compact('cart', 'cartItems', 'totalPrice', 'selectedAddress'));
    }

    // Update the placeOrder method to handle the location_id
    public function placeOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'shipping_address' => 'required',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);
        
        // Get the active cart
        $cart = Cart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
        }
        
        // Get the selected location
        $location = auth()->user()->locations()->where('location_id', $request->shipping_address)->first();
        
        if (!$location) {
            return redirect()->route('customer.checkout')->with('error', 'Please select a valid shipping address.');
        }
        
        // Format the shipping address
        $shippingAddress = $location->address_line1;
        if ($location->address_line2) {
            $shippingAddress .= ', ' . $location->address_line2;
        }
        $shippingAddress .= ', ' . $location->city . ', ' . $location->state . ' ' . $location->postal_code;
        
        // Calculate total amount
        $totalAmount = 0;
        foreach ($cart->cartItems as $item) {
            $totalAmount += $item->unit_price * $item->quantity;
        }
        
        // Add shipping cost
        $totalAmount += 5; // $5 shipping
        
        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'cart_id' => $cart->cart_id,
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'shipping_address' => $shippingAddress,
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

    // Add these methods to the CustomerViewController class
    
    // Add this method to your CustomerViewController class
    public function about()
    {
        return view('customer.about');
    }

    // Update the profile method to include orders
    public function profile()
    {
        $user = auth()->user();
        $locations = $user->locations()->get();
        
        // Get recent orders for profile page
        $recentOrders = Order::with(['orderItems.product', 'payment'])
                              ->where('user_id', auth()->id())
                              ->orderBy('order_date', 'desc')
                              ->limit(3)
                              ->get();
        
        return view('customer.profile.index', compact('user', 'locations', 'recentOrders'));
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
    
    /**
     * Display the services page.
     */
    // Add this method to CustomerViewController
    public function services()
    {
        $services = Service::where('active', 1)
            ->where('deleted', 0)  // Add this line to filter out deleted services
            ->get();
            
        return view('customer.services.index', compact('services'));
    }

    // Update the createAppointment method to handle service_id parameter
    public function createAppointment(Request $request) 
    { 
        $services = Service::all(); 
        $selectedServiceId = $request->query('service_id'); 
        
        return view('customer.appointments.create', [ 
            'services' => $services, 
            'selectedServiceId' => $selectedServiceId 
        ]); 
    }

    // Helper method to get available time slots
    private function getAvailableTimeSlots($date, $serviceId = null)
    {
        $date = Carbon::parse($date);
        $timeSlots = [];
        
        // If Sunday, return empty (assuming clinic closed on Sundays)
        if ($date->dayOfWeek === 0) {
            return $timeSlots;
        }
        
        // Get service duration if service_id is provided
        $serviceDuration = 60; // Default 1 hour
        if ($serviceId) {
            $service = Service::find($serviceId);
            if ($service) {
                $serviceDuration = $service->duration_minutes;
            }
        }
        
        // Business hours: 9 AM to 8 PM
        $startHour = 9; // 9 AM
        $endHour = 20; // 8 PM
        
        // Get all appointments for this date
        $appointments = Appointment::where('status', 'scheduled')
            ->whereDate('appointment_date', $date)
            ->get();
        
        // Generate time slots
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            // Generate slots at 30-minute intervals
            for ($minute = 0; $minute < 60; $minute += 30) {
                $slotStart = Carbon::parse($date)->hour($hour)->minute($minute)->second(0);
                
                // Skip if this time slot has already passed
                if ($slotStart < now()) {
                    continue;
                }
                
                // Calculate slot end time based on service duration
                $slotEnd = (clone $slotStart)->addMinutes($serviceDuration);
                
                // Skip if slot end time is after business hours
                if ($slotEnd->hour >= $endHour) {
                    continue;
                }
                
                // Change any instances of '$' to 'RM' in currency formatting
                // Check if this slot overlaps with any booked appointment
                $isAvailable = true;
                foreach ($appointments as $appointment) {
                    $appointmentStart = $appointment->appointment_date;
                    $appointmentEnd = $appointment->end_time;
                    
                    // Check for overlap
                    if (
                        ($slotStart >= $appointmentStart && $slotStart < $appointmentEnd) ||
                        ($slotEnd > $appointmentStart && $slotEnd <= $appointmentEnd) ||
                        ($slotStart <= $appointmentStart && $slotEnd >= $appointmentEnd)
                    ) {
                        $isAvailable = false;
                        break;
                    }
                }
                
                if ($isAvailable) {
                    $timeSlots[] = [
                        'time' => $slotStart->format('g:i A'),
                        'value' => $slotStart->format('H:i'),
                        'available' => true
                    ];
                }
            }
        }
        
        return $timeSlots;
    }

    // Update the storeAppointment method to handle payment redirection
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
            'terms_agreed' => 'required|accepted',
        ]);
        
        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        
        // Get service to calculate end time
        $service = Service::findOrFail($validated['service_id']);
        
        // Calculate end time based on service duration
        $endTime = (clone $appointmentDateTime)->addMinutes($service->duration_minutes);
        
        // Create appointment
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'appointment_date' => $appointmentDateTime,
            'end_time' => $endTime,
            'status' => 'scheduled',
            'notes' => $validated['notes'] ?? null,
        ]);
        
        // Redirect to payment page
        return redirect()->route('customer.appointment.payment', $appointment->appointment_id)
            ->with('success', 'Appointment scheduled successfully. Please complete your payment.');
    }

    // Add a new method for appointment payment
    public function showPayment(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== auth()->id()) {
            return redirect()->route('customer.appointments.index')
                ->with('error', 'You do not have permission to view this appointment.');
        }
        
        return view('customer.appointments.payment', compact('appointment'));
    }

    // Add a method to process appointment payment
    public function processPayment(Request $request, $appointmentId)
    {
        $appointment = Appointment::with('service')->findOrFail($appointmentId);
        
        // Check if this appointment belongs to the logged-in user
        if ($appointment->user_id !== auth()->id()) {
            return redirect()->route('customer.appointments')
                ->with('error', 'Unauthorized access to appointment.');
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,paypal,cash',
        ]);
        
        // Create a payment record
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'appointment_id' => $appointmentId,
            'amount' => $appointment->service->price,
            'payment_date' => now(),
            'payment_method' => $validated['payment_method'],
            'status' => 'completed',
            'transaction_id' => 'TRX-' . time() . '-' . auth()->id(),
        ]);
        
        return redirect()->route('customer.appointments')
            ->with('success', 'Payment completed successfully. Your appointment is confirmed.');
    }

    /**
     * Display the specified service details
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showService($id)
    {
        $service = Service::findOrFail($id);
        
        // Get available slots for this service
        $availableSlots = [];
        $startDate = now()->addDay(); // Start from tomorrow
        $endDate = now()->addDays(14); // Show availability for next 14 days
        
        // Get all booked appointments for this service in the date range
        $bookedAppointments = Appointment::where('service_id', $service->service_id)
            ->where('status', 'scheduled')
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->get();
        
        // Calculate available slots
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            // Skip if current day is Sunday (assuming clinic closed on Sundays)
            if ($currentDate->dayOfWeek === 0) {
                $currentDate->addDay();
                continue;
            }
            
            $dateStr = $currentDate->format('M d, Y');
            
            // Business hours: 9 AM to 8 PM, 1-hour slots
            $startHour = 9; // 9 AM
            $endHour = 20; // 8 PM
            
            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $slotStart = clone $currentDate;
                $slotStart->hour($hour)->minute(0)->second(0);
                
                // Skip if this time slot has already passed
                if ($slotStart < now()) {
                    continue;
                }
                
                // Calculate slot end time based on service duration
                $slotEnd = (clone $slotStart)->addMinutes($service->duration_minutes);
                
                // Check if this slot overlaps with any booked appointment
                $isAvailable = true;
                foreach ($bookedAppointments as $appointment) {
                    $appointmentStart = $appointment->appointment_date;
                    $appointmentEnd = $appointment->end_time;
                    
                    // Check for overlap
                    if (
                        ($slotStart >= $appointmentStart && $slotStart < $appointmentEnd) ||
                        ($slotEnd > $appointmentStart && $slotEnd <= $appointmentEnd) ||
                        ($slotStart <= $appointmentStart && $slotEnd >= $appointmentEnd)
                    ) {
                        $isAvailable = false;
                        break;
                    }
                }
                
                if ($isAvailable) {
                    $availableSlots[$dateStr][] = [
                        'time' => $slotStart->format('g:i A'),
                        'datetime' => $slotStart->format('Y-m-d H:i:s')
                    ];
                }
            }
            
            $currentDate->addDay();
        }
        
        // Get related services in the same category
        $relatedServices = Service::where('category', $service->category)
            ->where('service_id', '!=', $service->service_id)
            ->where('active', true)
            ->take(3)
            ->get();
        
        return view('customer.services.show', compact('service', 'availableSlots', 'relatedServices'));
    }
}