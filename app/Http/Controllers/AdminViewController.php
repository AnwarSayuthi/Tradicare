<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Product;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminViewController extends Controller
{
       
    /**
     * Display a listing of all orders
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function orders(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status');
        $dateRange = $request->input('date_range');
        $search = $request->input('search');
        
        // Start with a base query
        $ordersQuery = Order::with(['user', 'items.product', 'payments' => function($query) {
            $query->latest('payment_date');
        }]);
        
        // Apply filters if provided
        if ($status) {
            $ordersQuery->where('status', $status);
        }
        
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
                $ordersQuery->whereBetween('order_date', [$startDate, $endDate]);
            }
        }
        
        if ($search) {
            $ordersQuery->where(function($query) use ($search) {
                $query->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        
        // Get orders with pagination
        $orders = $ordersQuery->orderBy('order_date', 'desc')->paginate(10);
        
        // Get order statistics
        $totalOrders = Order::count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Calculate revenue
        $totalRevenue = Order::sum('total_amount');
        $monthlyRevenue = Order::whereMonth('order_date', Carbon::now()->month)
            ->whereYear('order_date', Carbon::now()->year)
            ->sum('total_amount');
        
        return view('admin.orders.index', compact(
            'orders', 
            'totalOrders', 
            'processingOrders', 
            'shippedOrders', 
            'deliveredOrders', 
            'cancelledOrders',
            'totalRevenue',
            'monthlyRevenue'
        ));
    }
    
    /**
     * Display the specified order
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function showOrder(Order $order)
    {
        // Load order with relationships
        $order->load(['user', 'items.product', 'payments', 'tracking', 'location']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Store a new customer
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCustomer(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tel_number' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);
    
        // Redirect back with success message
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully');
    }
    
    /**
     * Display customer management page
     *
     * @return \Illuminate\View\View
     */
    public function customerDetails()
    {
        // Get all customers with pagination
        $customers = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get customer statistics
        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')->where('status', 'active')->count();
        $inactiveCustomers = User::where('role', 'customer')->where('status', 'inactive')->count();
        
        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'inactiveCustomers'
        ));
    }
    
    /**
     * Display specific customer details
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function showCustomer(User $user)
    {
        // Load customer with relationships
        $user->load(['orders', 'appointments']);
        
        // Get customer statistics
        $totalOrders = $user->orders->count();
        $totalAppointments = $user->appointments->count();
        $totalSpent = $user->orders->sum('total_amount');
        
        return view('admin.customers.show', compact(
            'user',
            'totalOrders',
            'totalAppointments',
            'totalSpent'
        ));
    }
    
    /**
     * Update customer status (active/inactive)
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCustomerStatus(Request $request, User $user)
    {
        // Validate request
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        
        // Update user status
        $user->status = $request->status;
        $user->save();
        
        // Redirect back with success message
        return redirect()->back()->with('success', 'Customer status updated successfully');
    }
    
    /**
     * Display a listing of services
     *
     * @return \Illuminate\View\View
     */
    public function services()
    {
        // Get only non-deleted services
        $services = Service::notDeleted()->paginate(10);
        
        // Count services
        $totalServices = Service::notDeleted()->count();
        $activeServices = Service::notDeleted()->where('active', 1)->count();
        $inactiveServices = Service::notDeleted()->where('active', 0)->count();
        
        return view('admin.services.index', compact('services', 'totalServices', 'activeServices', 'inactiveServices'));
    }
    
    /**
     * Show the form for creating a new service
     *
     * @return \Illuminate\View\View
     */
    public function createService()
    {
        return view('admin.services.create');
    }
    
    /**
     * Store a newly created service
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15',
            'category' => 'required|string|in:traditional,massage,wellness',
            'active' => 'boolean',
        ]);
        
        $service = Service::create($validated);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully');
    }
    
    /**
     * Display the specified service
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showService($id)
    {
        $service = Service::findOrFail($id);
        
        // Get upcoming appointments for this service
        $upcomingAppointments = Appointment::where('service_id', $id)
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->with('user')
            ->take(5)
            ->get();
        
        return view('admin.services.show', compact('service', 'upcomingAppointments'));
    }
    
    /**
     * Show the form for editing the specified service
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editService($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }
    
    /**
     * Update the specified service
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateService(Request $request, $id)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15',
            'category' => 'required|string|in:traditional,massage,wellness',
            'active' => 'boolean',
        ]);
        
        $service = Service::findOrFail($id);
        $service->update($validated);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully');
    }
    
    /**
     * Soft delete the specified service
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        
        // Perform soft delete by setting the deleted flag
        $service->deleted = 1;
        $service->save();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service has been successfully deleted.');
    }
}