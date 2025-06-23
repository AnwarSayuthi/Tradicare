<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Location;
use App\Models\Tracking;
use App\Models\AvailableTime;
use App\Models\UnavailableTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        
        // Set date range
        if ($period === 'year') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
        } else {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
        }
        
        // Get dashboard data
        $metrics = $this->getMetrics($startDate, $endDate, $period, $year, $month);
        $charts = $this->getChartData($period, $year, $month);
        $recentData = $this->getRecentData();
        $analytics = $this->getAnalytics($startDate, $endDate);
        
        return view('admin.dashboard.index', compact(
            'metrics', 'charts', 'recentData', 'analytics', 'period', 'year', 'month'
        ));
    }
    
    private function getMetrics($startDate, $endDate, $period, $year, $month)
    {
        // Sales metrics
        $totalSales = Order::whereBetween('order_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('total_amount');
            
        $appointmentRevenue = Payment::whereNotNull('appointment_id')
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
            
        $totalRevenue = $totalSales + $appointmentRevenue;
        
        // Order metrics
        $totalOrders = Order::whereBetween('order_date', [$startDate, $endDate])->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')
            ->whereBetween('order_date', [$startDate, $endDate])->count();
            
        // Appointment metrics
        $totalAppointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->count();
        $completedAppointments = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$startDate, $endDate])->count();
        $appointmentRate = $totalAppointments > 0 ? ($completedAppointments / $totalAppointments) * 100 : 0;
        
        // Customer metrics
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])->count();
            
        // Product metrics
        $totalProducts = Product::where('active', true)->count();
        $lowStockProducts = Product::where('active', true)->where('stock_quantity', '<=', 10)->count();
        
        // Growth calculations
        $previousPeriodStart = $period === 'year' ? 
            Carbon::createFromDate($year - 1, 1, 1)->startOfDay() :
            Carbon::createFromDate($year, $month, 1)->subMonth()->startOfDay();
        $previousPeriodEnd = $period === 'year' ?
            Carbon::createFromDate($year - 1, 12, 31)->endOfDay() :
            Carbon::createFromDate($year, $month, 1)->subMonth()->endOfMonth()->endOfDay();
            
        $previousSales = Order::whereBetween('order_date', [$previousPeriodStart, $previousPeriodEnd])
            ->where('payment_status', 'paid')
            ->sum('total_amount');
            
        $salesGrowth = $previousSales > 0 ? (($totalSales - $previousSales) / $previousSales) * 100 : 0;
        
        return [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'appointmentRate' => round($appointmentRate, 1),
            'totalRevenue' => $totalRevenue,
            'totalCustomers' => $totalCustomers,
            'newCustomers' => $newCustomers,
            'totalAppointments' => $totalAppointments,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'salesGrowth' => round($salesGrowth, 1)
        ];
    }
    
    private function getChartData($period, $year, $month)
    {
        $salesData = [];
        $ordersData = [];
        $appointmentData = [];
        
        if ($period === 'year') {
            for ($i = 1; $i <= 12; $i++) {
                $monthStart = Carbon::createFromDate($year, $i, 1)->startOfDay();
                $monthEnd = Carbon::createFromDate($year, $i, 1)->endOfMonth()->endOfDay();
                
                $monthlySales = Order::whereBetween('order_date', [$monthStart, $monthEnd])
                    ->where('payment_status', 'paid')
                    ->sum('total_amount');
                $monthlyOrders = Order::whereBetween('order_date', [$monthStart, $monthEnd])->count();
                $monthlyAppointments = Appointment::whereBetween('appointment_date', [$monthStart, $monthEnd])->count();
                
                $salesData[] = ['label' => Carbon::createFromDate($year, $i, 1)->format('M'), 'value' => $monthlySales];
                $ordersData[] = ['label' => Carbon::createFromDate($year, $i, 1)->format('M'), 'value' => $monthlyOrders];
                $appointmentData[] = ['label' => Carbon::createFromDate($year, $i, 1)->format('M'), 'value' => $monthlyAppointments];
            }
        } else {
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            for ($i = 1; $i <= min($daysInMonth, 30); $i++) {
                $dayStart = Carbon::createFromDate($year, $month, $i)->startOfDay();
                $dayEnd = Carbon::createFromDate($year, $month, $i)->endOfDay();
                
                $dailySales = Order::whereBetween('order_date', [$dayStart, $dayEnd])
                    ->where('payment_status', 'paid')
                    ->sum('total_amount');
                $dailyOrders = Order::whereBetween('order_date', [$dayStart, $dayEnd])->count();
                $dailyAppointments = Appointment::whereBetween('appointment_date', [$dayStart, $dayEnd])->count();
                
                $salesData[] = ['label' => $i, 'value' => $dailySales];
                $ordersData[] = ['label' => $i, 'value' => $dailyOrders];
                $appointmentData[] = ['label' => $i, 'value' => $dailyAppointments];
            }
        }
        
        // Product chart data
        $productData = Product::select('category', DB::raw('COUNT(*) as count'))
            ->where('active', true)
            ->groupBy('category')
            ->get()
            ->map(function($item) {
                return ['label' => $item->category, 'value' => $item->count];
            });
            
        return [
            'sales' => $salesData,
            'orders' => $ordersData,
            'appointments' => $appointmentData,
            'products' => $productData
        ];
    }
    
    private function getRecentData()
    {
        // Recent orders with user info
        $recentOrders = Order::with('user')
            ->orderBy('order_date', 'desc')
            ->take(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->order_id,
                    'customer' => $order->user->name ?? 'Unknown',
                    'amount' => $order->total_amount,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'date' => $order->order_date->format('d/m/Y H:i')
                ];
            });
            
        // Recent appointments
        $recentAppointments = Appointment::with(['user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->appointment_id,
                    'customer' => $appointment->user->name ?? 'Unknown',
                    'service' => $appointment->service->service_name ?? 'Unknown Service',
                    'date' => $appointment->appointment_date->format('d/m/Y H:i'),
                    'status' => $appointment->status
                ];
            });
            
        return [
            'orders' => $recentOrders,
            'appointments' => $recentAppointments
        ];
    }
    
    private function getAnalytics($startDate, $endDate)
    {
        // Revenue by payment method
        $paymentMethods = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->payment_method => $item->total];
            });
            
        // Top services
        $topServices = Service::withCount(['appointments' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('appointment_date', [$startDate, $endDate]);
            }])
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get();
            
        // Customer locations
        $customerLocations = Location::select('city', DB::raw('COUNT(*) as count'))
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();
            
        return [
            'paymentMethods' => $paymentMethods,
            'topServices' => $topServices,
            'customerLocations' => $customerLocations
        ];
    }
    
    public function generateReport(Request $request)
    {
        $period = $request->input('period', 'month');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        
        // Set date range
        if ($period === 'year') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $dateLabel = $year;
        } else {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            $dateLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');
        }
        
        // Fix: Pass all 5 parameters to getMetrics
        $metrics = $this->getMetrics($startDate, $endDate, $period, $year, $month);
        $charts = $this->getChartData($period, $year, $month);
        $analytics = $this->getAnalytics($startDate, $endDate);
        
        $reportData = [
            'period' => $dateLabel,
            'metrics' => $metrics,
            'charts' => $charts,
            'analytics' => $analytics,
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s')
        ];
        
        return response()->json([
            'success' => true,
            'data' => $reportData
        ]);
    }
    
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        $order->update(['status' => $request->status]);
        
        return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
    }
}