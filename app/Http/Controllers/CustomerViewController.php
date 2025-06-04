<?php

namespace App\Http\Controllers;

// Add this line with the other use statements
use App\Services\ToyyibPayService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\AvailableTime;
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
            ->where('status', '!=', 'cancelled') // Add this line to exclude cancelled appointments
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

    // Update the placeOrder method to use the new Payment object approach
    /**
     * Process an order payment
     */
    public function placeOrder(Request $request)
{
    // Validate the request
    $request->validate([
        'location_id' => 'required|exists:locations,location_id',
        'cart_id' => 'required|exists:carts,cart_id',
        'payment_method' => 'required|in:toyyibpay,cash_on_delivery',
    ]);

    // Get the active cart using the cart_id from the request
    $cart = Cart::where('cart_id', $request->cart_id)
                ->where('user_id', auth()->id()) // Security check
                ->where('status', 'active')
                ->with('cartItems.product')
                ->firstOrFail();

    if ($cart->cartItems->isEmpty()) {
        return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
    }
    
    // Check if cart already has an order
    if (!$cart->order_id) {
        // Get the selected location using location_id
        $location = auth()->user()->locations()
                    ->where('location_id', $request->location_id)
                    ->firstOrFail();

        // Use the location's full address attribute
        $shippingAddress = $location->full_address;
        
        // Calculate total amount
        $totalAmount = $cart->getTotalPrice();

        // Add shipping cost
        $shippingCost = 5.00; // $5 shipping
        $totalAmount += $shippingCost;
        
        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'location_id' => $request->location_id, // Add this line to store location_id
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_status' => Order::PAYMENT_PENDING,
            'shipping_address' => $shippingAddress,
            'status' => Order::STATUS_PENDING,
            'cart_id' => $cart->cart_id
        ]);
        
        // Link cart to order
        $cart->order_id = $order->order_id;
        $cart->status = 'active';
        $cart->save();
    // After retrieving the order and before creating a new payment
    } else {
        $order = Order::findOrFail($cart->order_id);
        
        // Recalculate the total amount based on current cart items
        $currentTotalAmount = $cart->getTotalPrice() + 5.00; // Adding $5 shipping
        
        // If the cart total has changed, update the order total_amount
        if (abs($currentTotalAmount - $order->total_amount) > 0.01) { // Using small epsilon for float comparison
            $order->total_amount = $currentTotalAmount;
            $order->save();
        }
        
        $totalAmount = $order->total_amount;
    }

    // Check for existing pending payment
    $payment = Payment::where('order_id', $order->order_id)
                     ->where('status', Payment::STATUS_PENDING)
                     ->first();

    // If payment exists but amount doesn't match current total, mark it as failed and create a new one
    if ($payment && abs($payment->amount - $totalAmount) > 0.01) {
        $payment->status = Payment::STATUS_FAILED;
        $payment->payment_details = array_merge($payment->payment_details ?? [], [
            'failure_reason' => 'Payment amount does not match current cart total'
        ]);
        $payment->save();
        
        // Set payment to null so a new one will be created
        $payment = null;
    }
    // Create new payment if none exists
    if (!$payment) {
        // Create a pending payment record
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'appointment_id' => $appointment->appointment_id,
            'amount' => $service->price,
            'payment_date' => now(),
            'payment_method' => Payment::METHOD_TOYYIBPAY, // Set a default method
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => 'TXN' . time() . rand(1000, 9999)
        ]);
    }
    // Handle payment based on method
    if ($request->payment_method === Payment::METHOD_TOYYIBPAY) {
        // Check if payment already has a billcode
        if (!empty($payment->billcode)) {
            // Use existing billcode to redirect to payment page
            $toyyibpay = new ToyyibPayService();
            return redirect($toyyibpay->getPaymentUrl($payment->billcode));
        }
        
        // Initiate ToyyibPay payment if no billcode exists
        $toyyibpay = new ToyyibPayService();

        $billResponse = $toyyibpay->createBill($payment);
        if (isset($billResponse[0]['BillCode'])) {
            // Redirect to ToyyibPay payment page
            return redirect($toyyibpay->getPaymentUrl($billResponse[0]['BillCode']));
        }
    
        // If payment initialization failed
        $payment->status = Payment::STATUS_FAILED;
        $payment->save();
        
        $order->payment_status = Order::PAYMENT_FAILED;
        $order->status = Order::STATUS_CANCELLED;
        $order->save();
        
        return redirect()->route('customer.cart')->with('error', 'Payment initialization failed. Please try again.');
    } else {
        // For cash on delivery
        $order->status = Order::STATUS_PROCESSING;
        $order->save();
        
        return redirect()->route('customer.orders')->with('success', 'Order placed successfully! You will pay upon delivery.');
    }
}

    public function profile(Request $request)
    {
        $user = auth()->user();
        $locations = $user->locations()->get();
        
        // Use paginate instead of get
        $orders = Order::with(['items.product', 'payments', 'tracking'])
                      ->where('user_id', auth()->id())
                      ->orderBy('order_date', 'desc')
                      ->paginate(10); // Show 10 orders per page
        
        return view('customer.profile.index', compact('user', 'locations', 'orders'));
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
    
    /**
     * Show payment page with appointment details
     */
    public function showPayment(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date',
            'available_time_id' => 'required|exists:available_times,available_time_id',
            'mobile_number' => 'required|string',
        ]);
    
        $service = Service::findOrFail($request->service_id);
        $timeSlot = AvailableTime::findOrFail($request->available_time_id);
        
        // Create appointment data array
        $appointmentData = [
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'available_time_id' => $request->available_time_id,
            'mobile_number' => $request->mobile_number,
            'notes' => $request->notes,
        ];
        
        // Store appointment data in session
        session(['appointment_data' => $appointmentData]);
    
        // Pass appointmentData to the view along with other variables
        return view('customer.appointments.payment', compact('service', 'timeSlot', 'appointmentData'));
    }

    /**
     * Process payment and create appointment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:toyyibpay,cash',
        ]);

        $appointmentData = session('appointment_data');
        if (!$appointmentData) {
            return redirect()->route('customer.appointments.create')
                ->with('error', 'Session expired. Please try again.');
        }

        // Get service details
        $service = Service::findOrFail($appointmentData['service_id']);
        $timeSlot = AvailableTime::findOrFail($appointmentData['available_time_id']);
        
        // Create appointment
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $appointmentData['service_id'],
            'appointment_date' => $appointmentData['appointment_date'],
            'available_time_id' => $appointmentData['available_time_id'],
            'status' => 'pending',
            'notes' => $appointmentData['notes'] ?? null,
        ]);

        // Create payment record
        $payment = Payment::create([
            'appointment_id' => $appointment->appointment_id,
            'user_id' => auth()->id(),
            'amount' => $service->price,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => 'TXN' . time() . rand(1000, 9999)
        ]);

        // Handle payment based on method
        if ($request->payment_method === 'toyyibpay') {
            // Initiate ToyyibPay payment
            $toyyibpay = new ToyyibPayService();
            $billResponse = $toyyibpay->createBill($payment);

            if (isset($billResponse[0]['BillCode'])) {
                // Update payment with billcode
                $payment->update([
                    'billcode' => $billResponse[0]['BillCode'],
                    'transaction_id' => $billResponse[0]['BillCode']
                ]);
                
                // Clear session data
                session()->forget('appointment_data');
                
                // Redirect to ToyyibPay
                $baseUrl = env('TOYYIBPAY_SANDBOX', true) ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
                return redirect($baseUrl . '/' . $billResponse[0]['BillCode']);
            }

            // If payment failed
            $appointment->delete();
            $payment->delete();
            return redirect()->route('customer.appointments.create')
                ->with('error', 'Payment initialization failed. Please try again.');
        } else {
            // For cash payment
            $payment->update(['status' => 'pending']);
            $appointment->update(['status' => 'scheduled']);
            
            // Clear session data
            session()->forget('appointment_data');
            
            return redirect()->route('customer.appointments.payment.success')
                ->with('success', 'Appointment booked successfully! You will pay with cash on arrival.');
        }
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess()
    {
        return view('customer.appointments.payment-success');
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
        $selectedDate = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::today();
        
        // Get available time slots if both service and date are selected
        $availableTimeSlots = [];
        if ($selectedServiceId && $selectedDate) {
            $availableTimeSlots = $this->getAvailableTimeSlots($selectedDate->format('Y-m-d'), $selectedServiceId);
        }
        
         // Group time slots into morning and afternoon
        $morningSlots = [];
        $afternoonSlots = [];
        
        foreach ($availableTimeSlots as $slot) {
            // Extract the time from the formatted time string (e.g., "9:00 AM - 10:00 AM")
            $timeString = explode(' - ', $slot['time'])[0]; // Get the start time part
            $hour = (int)Carbon::createFromFormat('g:i A', $timeString)->format('H');
            
            if ($hour < 12) {
                $morningSlots[] = $slot;
            } else {
                $afternoonSlots[] = $slot;
            }
        }
        
        return view('customer.appointments.create', [ 
            'services' => $services, 
            'selectedServiceId' => $selectedServiceId,
            'selectedDate' => $selectedDate,
            'morningSlots' => $morningSlots,
            'afternoonSlots' => $afternoonSlots
        ]); 
    }

    // Helper method to get available time slots
    // Update the getAvailableTimeSlots method to use AvailableTime model
    private function getAvailableTimeSlots($date, $serviceId = null)
{
    $date = Carbon::parse($date);
    $timeSlots = [];
    
    // Log the current time for comparison
    \Log::info('Current time: ' . now()->format('Y-m-d H:i:s'));
    \Log::info('Requested date: ' . $date->format('Y-m-d'));
    
    // If Sunday, return empty (assuming clinic closed on Sundays)
    if ($date->dayOfWeek === 0) {
        \Log::info('Sunday detected, returning empty slots');
        return $timeSlots;
    }
    
    // Get all available time slots from the database
    $availableTimes = AvailableTime::all();
    \Log::info('Total available time slots in database: ' . $availableTimes->count());
    
    // Get all unavailable times for this date
    $unavailableTimes = \App\Models\UnavailableTime::where('date', $date->format('Y-m-d'))
        ->pluck('available_time_id')
        ->toArray();
    \Log::info('Unavailable time slots for this date: ' . json_encode($unavailableTimes));
    
    // Get all booked appointments for this date
    $bookedAppointments = Appointment::where('status', 'scheduled')
        ->whereDate('appointment_date', $date)
        ->pluck('available_time_id')
        ->toArray();
    \Log::info('Booked appointments for this date: ' . json_encode($bookedAppointments));
    
    foreach ($availableTimes as $availableTime) {
        \Log::info('Processing time slot ID: ' . $availableTime->available_time_id . 
                 ', Start: ' . $availableTime->start_time->format('H:i:s') . 
                 ', End: ' . $availableTime->end_time->format('H:i:s'));
        
        // Skip if this time slot is marked as unavailable for this date
        if (in_array($availableTime->available_time_id, $unavailableTimes)) {
            \Log::info('Skipping - marked as unavailable for this date');
            continue;
        }
        
        // Skip if this time slot is already booked
        if (in_array($availableTime->available_time_id, $bookedAppointments)) {
            \Log::info('Skipping - already booked');
            continue;
        }
        
        // Format the start and end times
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $availableTime->start_time->format('H:i:s'));
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $availableTime->end_time->format('H:i:s'));
        
        \Log::info('Comparing times - Start time: ' . $startTime->format('Y-m-d H:i:s') . 
                 ', Current time: ' . now()->format('Y-m-d H:i:s') . 
                 ', Is past? ' . ($startTime < now() ? 'Yes' : 'No'));
        
        // Skip if this time slot has already passed
        if ($startTime < now()) {
            \Log::info('Skipping - time slot has already passed');
            continue;
        }
        
        // Add to morning or afternoon slots directly in the array
        $timeSlots[] = [
            'time' => $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A'),
            'value' => $availableTime->available_time_id, // Use the available_time_id as the value
            'available' => true
        ];
        \Log::info('Added time slot to available slots');
    }
    
    \Log::info('Final number of available time slots: ' . count($timeSlots));
    return $timeSlots;
}

    // Update the storeAppointment method to handle payment redirection
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date',
            'available_time_id' => 'required|exists:available_times,available_time_id',
            'tel_number' => 'required|string',
        ]);
        
        // Get the available time slot
        $availableTime = AvailableTime::findOrFail($validated['available_time_id']);
        
        // Get service
        $service = Service::findOrFail($validated['service_id']);
        
        // Create appointment with pending_payment status
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'available_time_id' => $validated['available_time_id'],
            'appointment_date' => Carbon::parse($validated['appointment_date']),
            'status' => 'scheduled', // Changed to an allowed value
            'notes' => $request->notes ?? null,
        ]);
        
        // Create a pending payment record
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'appointment_id' => $appointment->appointment_id,
            'amount' => $service->price,
            'payment_date' => now(),
            'payment_method' => Payment::METHOD_TOYYIBPAY, // Set a default method
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => 'TXN' . time() . rand(1000, 9999)
        ]);
        
        // Redirect to payment page
        return redirect()->route('customer.appointments.payment', $appointment->appointment_id)
            ->with('success', 'Appointment reserved. Please complete your payment to confirm booking.');
    }



    // Add a method to process appointment payment
    /**
     * Create a new appointment and process payment in one step
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAppointmentPayment(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date|after:today',
            'available_time_id' => 'required|exists:available_times,available_time_id',
            'mobile_number' => 'required|string',
            'payment_method' => 'required|in:toyyibpay,cash',
        ]);
    
        // Get service details
        $service = Service::findOrFail($request->service_id);
        $timeSlot = AvailableTime::findOrFail($request->available_time_id);
        
        // Create appointment
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'available_time_id' => $request->available_time_id,
            'start_time' => $timeSlot->start_time,
            'end_time' => $timeSlot->end_time,
            'tel_number' => $request->tel_number,
            'status' => 'pending',
            'notes' => $request->notes ?? null,
        ]);
        
        // Create payment record
        $payment = Payment::create([
            'appointment_id' => $appointment->appointment_id,
            'user_id' => auth()->id(),
            'amount' => $service->price,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => 'TXN' . time() . rand(1000, 9999)
        ]);
        
        // Handle payment based on method
        if ($request->payment_method === 'toyyibpay') {
            // Initiate ToyyibPay payment
            $toyyibpay = new ToyyibPayService();
            $billResponse = $toyyibpay->createBill($payment);
    
            if (isset($billResponse[0]['BillCode'])) {
                // Update payment with billcode
                $payment->update([
                    'billcode' => $billResponse[0]['BillCode'],
                    'transaction_id' => $billResponse[0]['BillCode']
                ]);
                
                // Determine the correct payment URL (sandbox or production)
                $baseUrl = env('TOYYIBPAY_SANDBOX', true) ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
                
                return response()->json([
                    'success' => true,
                    'show_popup' => true,
                    'payment_url' => $baseUrl . '/' . $billResponse[0]['BillCode']
                ]);
            }
    
            // If payment failed
            $appointment->delete();
            $payment->delete();
            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed. Please try again.'
            ]);
        } else {
            // For cash payment
            $payment->update(['status' => 'pending']);
            $appointment->update(['status' => 'scheduled']);
            
            return response()->json([
                'success' => true,
                'show_success' => true,
                'message' => 'Appointment booked successfully! You will pay with cash on arrival.'
            ]);
        }
    }
    
    /**
     * Process payment for an existing appointment
     *
     * @param Request $request
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function processAppointmentPayment(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to process this payment.'
            ]);
        }
        
        // Create or update payment record
        $payment = Payment::updateOrCreate(
            ['appointment_id' => $appointment->appointment_id],
            [
                'user_id' => auth()->id(),
                'amount' => $appointment->service->price,
                'payment_date' => now(),
                'payment_method' => $request->payment_method,
                'status' => Payment::STATUS_PENDING,
                'transaction_id' => 'TXN' . time() . rand(1000, 9999)
            ]
        );
        
        // Handle payment based on method
        if ($request->payment_method === 'toyyibpay') {
            // Initiate ToyyibPay payment
            $toyyibpay = new ToyyibPayService();
            $billResponse = $toyyibpay->createBill($payment);

            if (isset($billResponse[0]['BillCode'])) {
                // Update payment with billcode
                $payment->update([
                    'billcode' => $billResponse[0]['BillCode'],
                    'transaction_id' => $billResponse[0]['BillCode']
                ]);
                
                // Determine the correct payment URL (sandbox or production)
                $baseUrl = env('TOYYIBPAY_SANDBOX', true) ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
                
                return response()->json([
                    'success' => true,
                    'show_popup' => true,
                    'payment_url' => $baseUrl . '/' . $billResponse[0]['BillCode']
                ]);
            }

            // If payment failed
            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed. Please try again.'
            ]);
        } else {
            // For cash payment
            $payment->update(['status' => 'pending']);
            $appointment->update(['status' => 'scheduled']);
            
            return response()->json([
                'success' => true,
                'show_success' => true,
                'message' => 'Appointment booked successfully! You will pay with cash on arrival.'
            ]);
        }
    }


    // Update the paymentCallback method to handle both order and appointment payments
    public function paymentCallback(Request $request)
    {
        $billCode = $request->billcode;
        $status = $request->status_id;
    
        $payment = Payment::where('transaction_id', $billCode)->firstOrFail();
        
        // Verify payment amount against cart total if it's an order payment
        if ($payment->order_id && $payment->order && $payment->order->cart) {
            $order = $payment->order;
            $cart = $order->cart;
            
            // Calculate current cart total
            $cartTotal = $cart->getTotalPrice() + 5.00; // Adding $5 shipping
            
            // If cart total doesn't match payment amount, mark payment as failed
            if (abs($cartTotal - $payment->amount) > 0.01) { // Using small epsilon for float comparison
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
                    'payment_details' => array_merge($payment->payment_details ?? [], $request->all(), [
                        'failure_reason' => 'Payment amount does not match cart total'
                    ])
                ]);
                
                $order->update(['payment_status' => Order::PAYMENT_FAILED]);
                
                return redirect()->route('customer.orders')
                    ->with('error', 'Payment failed: Amount mismatch. Please try again or contact support.');
            }
        }
        
        if ($status == 1) {
            // Payment successful
            $payment->update(['status' => Payment::STATUS_COMPLETED]);
            
            // Handle order payment
            if ($payment->order_id) {
                $payment->order->update(['payment_status' => Order::PAYMENT_PAID, 'status' => Order::STATUS_PROCESSING]);
                return redirect()->route('customer.orders')
                    ->with('success', 'Payment successful! Your order is being processed.');
            }
            
            // Handle appointment payment
            if ($payment->appointment_id) {
                $payment->appointment->update(['status' => 'scheduled']);
                return redirect()->route('customer.appointments.index')
                    ->with('success', 'Payment successful! Your appointment has been confirmed.');
            }
        }
    
        // Payment failed
        $payment->update(['status' => 'failed']);
        
        // Handle order payment failure
        if ($payment->order_id) {
            $payment->order->update(['payment_status' => 'failed']);
            return redirect()->route('customer.orders')
                ->with('error', 'Payment failed. Please try again or contact support.');
        }
        
        // Handle appointment payment failure
        if ($payment->appointment_id) {
            $payment->appointment->update(['status' => 'cancelled']);
            return redirect()->route('customer.appointments.index')
                ->with('error', 'Payment failed. Please try again or contact support.');
        }
        
        return redirect()->route('landing')
            ->with('error', 'Payment failed. Please try again or contact support.');
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


    /**
     * Cancel an order
     * 
     * @param int $id Order ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelOrder($id)
    {
        // Find the order
        $order = Order::where('order_id', $id)
                     ->where('user_id', auth()->id())
                     ->first();
        
        if (!$order) {
            return redirect()->route('customer.orders')
                ->with('error', 'Order not found.');
        }
        
        // Check if order can be cancelled (allow both pending and processing orders to be cancelled)
        if ($order->status !== Order::STATUS_PROCESSING && $order->status !== Order::STATUS_PENDING) {
            return redirect()->route('customer.orders')
                ->with('error', 'Only pending or processing orders can be cancelled.');
        }
        
        // Update order status
        $order->status = Order::STATUS_CANCELLED;
        $order->save();
        
        // If there's a pending payment, mark it as failed
        $pendingPayment = Payment::where('order_id', $order->order_id)
                              ->where('status', Payment::STATUS_PENDING)
                              ->first();
        
        if ($pendingPayment) {
            $pendingPayment->status = Payment::STATUS_FAILED;
            $pendingPayment->save();
        }
        
        return redirect()->route('customer.orders')
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Mark an order as received by the customer
     * 
     * @param int $id Order ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function receiveOrder($id)
    {
        // Find the order
        $order = Order::where('order_id', $id)
                 ->where('user_id', auth()->id())
                 ->first();
        
        if (!$order) {
            return redirect()->route('customer.orders')
                ->with('error', 'Order not found.');
        }
        
        // Check if order can be marked as received (only shipped orders)
        if ($order->status !== Order::STATUS_SHIPPED) {
            return redirect()->route('customer.orders')
                ->with('error', 'Only shipped orders can be marked as received.');
        }
        
        // Update order status to delivered/completed
        $order->status = Order::STATUS_DELIVERED;
        $order->save();
        
        return redirect()->back()
            ->with('success', 'Order marked as received successfully.');
    }
      /**
     * Display the details of a specific order
     * 
     * @param int $id Order ID
     * @return \Illuminate\View\View
     */
    public function orderDetails($id)
    {
        // Find the order
        $order = Order::where('order_id', $id)
                     ->where('user_id', auth()->id())
                     ->with(['items.product', 'payments', 'tracking'])
                     ->firstOrFail();
        
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Get unavailable times for a specific date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnavailableTimes(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json([]);
        }
        
        $unavailableTimes = \App\Models\UnavailableTime::where('date', $date)
            ->with('availableTime')
            ->get()
            ->map(function($unavailableTime) {
                return [
                    'id' => $unavailableTime->unavailable_time_id,
                    'date' => $unavailableTime->date->format('Y-m-d'),
                    'start_time' => $unavailableTime->availableTime->start_time->format('H:i'),
                    'end_time' => $unavailableTime->availableTime->end_time->format('H:i')
                ];
            });
        
        return response()->json($unavailableTimes);
    }

    /**
     * Cancel an appointment
     * 
     * @param int $id Appointment ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelAppointment($id)
    {
        // Find the appointment
        $appointment = \App\Models\Appointment::where('appointment_id', $id)
                     ->where('user_id', auth()->id())
                     ->first();
        
        if (!$appointment) {
            return redirect()->route('customer.appointments.index')
                ->with('error', 'Appointment not found.');
        }
        
        // Check if appointment can be cancelled (only scheduled appointments)
        if ($appointment->status !== 'scheduled') {
            return redirect()->route('customer.appointments.index')
                ->with('error', 'Only scheduled appointments can be cancelled.');
        }
        
        // Update appointment status
        $appointment->status = 'cancelled';
        $appointment->save();
        
        // If there's a pending payment, mark it as failed
        $pendingPayment = \App\Models\Payment::where('appointment_id', $appointment->appointment_id)
                              ->where('status', \App\Models\Payment::STATUS_PENDING)
                              ->first();
        
        if ($pendingPayment) {
            $pendingPayment->status = \App\Models\Payment::STATUS_FAILED;
            $pendingPayment->save();
        }
        
        return redirect()->route('customer.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }
}
