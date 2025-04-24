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
    public function dashboard(Request $request)
    {
        // Get time period filter (default: current month)
        $period = $request->input('period', 'month');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        
        // Set date range based on period
        if ($period === 'year') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $dateLabel = $year;
        } else {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            $dateLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');
        }
        
        // Get counts for dashboard statistics
        $customerCount = User::where('role', 'customer')->count();
        
        // Total sales (orders + appointments)
        $orderSales = Order::whereBetween('order_date', [$startDate, $endDate])
            ->sum('total_amount');
            
        $appointmentSales = Payment::whereNotNull('appointment_id')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
            
        $totalSales = $orderSales + $appointmentSales;
        
        // Product and order counts
        $productCount = Product::where('active', true)->count();
        $orderCount = Order::whereBetween('order_date', [$startDate, $endDate])->count();
        $appointmentCount = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->count();
        
        // Customer experience/satisfaction (placeholder - you might want to implement a real rating system)
        $customerSatisfaction = 85; // Placeholder value (85%)
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('order_date', 'desc')
            ->take(5)
            ->get();
            
        // Get top selling products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->with('product')
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('order_date', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();
            
        // Get sales data for chart
        $salesData = [];
        $visitorData = []; // Placeholder for visitor data
        
        if ($period === 'year') {
            // Monthly data for the selected year
            for ($i = 1; $i <= 12; $i++) {
                $monthStart = Carbon::createFromDate($year, $i, 1)->startOfDay();
                $monthEnd = Carbon::createFromDate($year, $i, 1)->endOfMonth()->endOfDay();
                
                $monthlySales = Order::whereBetween('order_date', [$monthStart, $monthEnd])->sum('total_amount');
                $monthlySales += Payment::whereNotNull('appointment_id')
                    ->whereBetween('payment_date', [$monthStart, $monthEnd])
                    ->sum('amount');
                    
                $salesData[] = [
                    'label' => Carbon::createFromDate($year, $i, 1)->format('M'),
                    'value' => round($monthlySales, 2)
                ];
                
                // Placeholder visitor data (random for demonstration)
                $visitorData[] = [
                    'label' => Carbon::createFromDate($year, $i, 1)->format('M'),
                    'value' => rand(50, 200)
                ];
            }
        } else {
            // Daily data for the selected month
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $dayStart = Carbon::createFromDate($year, $month, $i)->startOfDay();
                $dayEnd = Carbon::createFromDate($year, $month, $i)->endOfDay();
                
                $dailySales = Order::whereBetween('order_date', [$dayStart, $dayEnd])->sum('total_amount');
                $dailySales += Payment::whereNotNull('appointment_id')
                    ->whereBetween('payment_date', [$dayStart, $dayEnd])
                    ->sum('amount');
                    
                $salesData[] = [
                    'label' => $i,
                    'value' => round($dailySales, 2)
                ];
                
                // Placeholder visitor data (random for demonstration)
                $visitorData[] = [
                    'label' => $i,
                    'value' => rand(5, 30)
                ];
            }
        }
        
        // Get payment status distribution
        $paymentStatusData = Order::select('payment_status', DB::raw('COUNT(*) as count'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy('payment_status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->payment_status => $item->count];
            })
            ->toArray();
            
        // Get order status distribution
        $orderStatusData = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            })
            ->toArray();
        
        return view('admin.dashboard', [
            'customerCount' => $customerCount,
            'orderCount' => $orderCount,
            'appointmentCount' => $appointmentCount,
            'productCount' => $productCount,
            'totalSales' => $totalSales,
            'customerSatisfaction' => $customerSatisfaction,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'salesData' => json_encode($salesData),
            'visitorData' => json_encode($visitorData),
            'paymentStatusData' => json_encode($paymentStatusData),
            'orderStatusData' => json_encode($orderStatusData),
            'period' => $period,
            'year' => $year,
            'month' => $month,
            'dateLabel' => $dateLabel,
            'years' => range(Carbon::now()->year - 5, Carbon::now()->year),
            'months' => array_map(function($m) { 
                return ['value' => $m, 'label' => Carbon::createFromDate(null, $m, 1)->format('F')]; 
            }, range(1, 12))
        ]);
    }
    
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
        $ordersQuery = Order::with(['user', 'orderItems.product', 'payment']);
        
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
        $order->load(['user', 'orderItems.product', 'payment']);
        
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
        $services = Service::orderBy('service_name')->paginate(10);
        return view('admin.services.index', compact('services'));
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
            'icon' => 'nullable|string|max:50',
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
            'icon' => 'nullable|string|max:50',
            'active' => 'boolean',
        ]);
        
        $service = Service::findOrFail($id);
        $service->update($validated);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully');
    }
    
    /**
     * Remove the specified service
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        
        // Check if there are any appointments using this service
        $appointmentsCount = Appointment::where('service_id', $id)->count();
        
        if ($appointmentsCount > 0) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Cannot delete service. It has ' . $appointmentsCount . ' appointments associated with it.');
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully');
    }
}