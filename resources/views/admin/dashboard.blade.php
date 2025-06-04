@extends('admin_layout')

@section('title', 'Admin Dashboard - Tradicare')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
<style>
    .stats-card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #2DCE89 0%, #2DCECC 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #11CDEF 0%, #1171EF 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #FB6340 0%, #FBB140 100%);
    }
    
    /* Updated color theme */
    .bg-primary-light {
        background-color: rgba(110, 142, 251, 0.15);
    }
    
    .bg-success-light {
        background-color: rgba(45, 206, 137, 0.15);
    }
    
    .bg-info-light {
        background-color: rgba(17, 205, 239, 0.15);
    }
    
    .bg-warning-light {
        background-color: rgba(251, 99, 64, 0.15);
    }
    
    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.15);
    }
    
    .text-primary {
        color: #6e8efb !important;
    }
    
    .text-success {
        color: #2DCE89 !important;
    }
    
    .text-info {
        color: #11CDEF !important;
    }
    
    .text-warning {
        color: #FB6340 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .btn-primary {
        background-color: #6e8efb;
        border-color: #6e8efb;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: #5a7df9;
        border-color: #5a7df9;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }
    
    .progress-bar.bg-success {
        background: linear-gradient(to right, #2DCE89, #2DCECC) !important;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .top-product-item {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .top-product-item:hover {
        background-color: rgba(110, 142, 251, 0.05);
        border-left-color: #6e8efb;
    }
    
    .report-card {
        background-color: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        height: 100%;
    }
    
    .report-card-header {
        padding: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .report-card-body {
        padding: 20px;
    }
    
    .filter-container {
        background-color: #fff;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .donut-chart-container {
        position: relative;
        height: 200px;
        width: 100%;
    }
    
    /* Card hover effects */
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .card:hover {
        box-shadow: 0 8px 25px rgba(110, 142, 251, 0.1);
    }
    
    /* Badge styles */
    .badge.bg-warning {
        background-color: #FBB140 !important;
        color: #fff;
    }
    
    .badge.bg-info {
        background-color: #11CDEF !important;
    }
    
    .badge.bg-primary {
        background-color: #6e8efb !important;
    }
    
    .badge.bg-success {
        background-color: #2DCE89 !important;
    }
    
    .badge.bg-danger {
        background-color: #dc3545 !important;
    }
    
    /* Form controls */
    .form-select:focus, .form-control:focus {
        border-color: #6e8efb;
        box-shadow: 0 0 0 0.25rem rgba(110, 142, 251, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Date Filter -->
    <div class="filter-container">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="period" class="form-label">Report Period</label>
                <select name="period" id="period" class="form-select" onchange="updateFilter(this)">
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Monthly</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
            
            @if($period == 'month')
            <div class="col-md-3">
                <label for="month" class="form-label">Month</label>
                <select name="month" id="month" class="form-select" onchange="updateFilter(this)">
                    @foreach($months as $m)
                        <option value="{{ $m['value'] }}" {{ $month == $m['value'] ? 'selected' : '' }}>{{ $m['label'] }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="col-md-3">
                <label for="year" class="form-label">Year</label>
                <select name="year" id="year" class="form-select" onchange="updateFilter(this)">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.dashboard', ['period' => $period, 'year' => $year, 'month' => $month, 'export' => 'pdf']) }}" class="btn btn-primary">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export Report
                </a>
            </div>
        </form>
    </div>

    <!-- Store Details -->
    <div class="card mb-4" style="background-image: url('{{ asset('image/bg-pattern.jpg') }}'); background-size: cover; background-position: center;">
        <div class="card-body p-4">
            <h4 class="mb-3">Store Details</h4>
            <p class="text-muted">General Information About The Store - {{ $dateLabel }}</p>
            
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-0">Total Sales</h6>
                                    <h2 class="mb-0">RM{{ number_format($totalSales, 2) }}</h2>
                                </div>
                                <div class="stats-icon bg-gradient-primary text-white">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                            </div>
                            <p class="text-sm mb-0">
                                <span class="text-success me-2"><i class="bi bi-arrow-up"></i> 3.48%</span>
                                <span class="text-muted">From previous period</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-0">Available Products</h6>
                                    <h2 class="mb-0">{{ $productCount }}</h2>
                                </div>
                                <div class="stats-icon bg-gradient-success text-white">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                            <p class="text-sm mb-0">
                                <span class="text-success me-2"><i class="bi bi-arrow-up"></i> 1.10%</span>
                                <span class="text-muted">From previous period</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-0">Total Orders</h6>
                                    <h2 class="mb-0">{{ $orderCount }}</h2>
                                </div>
                                <div class="stats-icon bg-gradient-info text-white">
                                    <i class="bi bi-cart"></i>
                                </div>
                            </div>
                            <p class="text-sm mb-0">
                                <span class="text-danger me-2"><i class="bi bi-arrow-down"></i> 0.82%</span>
                                <span class="text-muted">From previous period</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-0">Customer Satisfaction</h6>
                                    <h2 class="mb-0">{{ $customerSatisfaction }}%</h2>
                                </div>
                                <div class="stats-icon bg-gradient-warning text-white">
                                    <i class="bi bi-emoji-smile"></i>
                                </div>
                            </div>
                            <p class="text-sm mb-0">
                                <span class="text-success me-2"><i class="bi bi-arrow-up"></i> 4.75%</span>
                                <span class="text-muted">From previous period</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Performance Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Sales Performance</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartPeriodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $period == 'month' ? 'Daily' : 'Monthly' }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="chartPeriodDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['period' => 'month', 'year' => $year, 'month' => $month]) }}">Daily</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['period' => 'year', 'year' => $year]) }}">Monthly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Financial Status -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Financial Status</h6>
                </div>
                <div class="card-body">
                    <h3 class="mb-3">RM{{ number_format($totalSales * 0.85, 2) }}</h3>
                    <p class="text-muted">You can transfer this money to your bank account.</p>
                    
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-primary">
                            <i class="bi bi-bank me-2"></i> Settlement
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Sales</span>
                            <span>RM{{ number_format($totalSales, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Platform Fee (15%)</span>
                            <span>-RM{{ number_format($totalSales * 0.15, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending Clearance</span>
                            <span>-RM{{ number_format($totalSales * 0.05, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span><strong>Available for Withdrawal</strong></span>
                            <span><strong>RM{{ number_format($totalSales * 0.8, 2) }}</strong></span>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <img src="{{ asset('image/analytics.svg') }}" alt="Analytics" style="max-width: 80%; height: 150px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Order ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td class="ps-3">
                                        <p class="text-xs font-weight-bold mb-0">#{{ $order->order_id }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $order->user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $order->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">RM{{ number_format($order->total_amount, 2) }}</p>
                                    </td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-primary">Shipped</span>
                                        @elseif($order->status == 'delivered' || $order->status == 'completed')
                                            <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->order_id) }}" class="btn btn-sm btn-info">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order & Payment Status -->
        <div class="col-lg-4">
            <div class="row">
                <!-- Order Status -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Order Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="donut-chart-container">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Status -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Payment Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="donut-chart-container">
                                <canvas id="paymentStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Top Selling Products -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @foreach($topProducts as $index => $product)
                    <div class="top-product-item p-3 {{ $index < count($topProducts) - 1 ? 'border-bottom' : '' }}">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar avatar-md rounded-circle bg-light">
                                    <span class="text-dark">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            <div class="col">
                                <h6 class="mb-0">{{ $product->product_name }}</h6>
                                <p class="text-sm text-muted mb-0">{{ $product->total_quantity }} units sold</p>
                            </div>
                            <div class="col-auto">
                                <h6 class="mb-0">RM{{ number_format($product->total_sales, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if(count($topProducts) === 0)
                    <div class="text-center py-4">
                        <p class="mb-0">No product sales data available for this period.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Additional Stats -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Business Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="avatar avatar-md rounded-circle bg-primary-light">
                                        <i class="bi bi-people text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-muted mb-0">Total Customers</p>
                                    <h5 class="mb-0">{{ $customerCount }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="avatar avatar-md rounded-circle bg-success-light">
                                        <i class="bi bi-calendar-check text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-muted mb-0">Appointments</p>
                                    <h5 class="mb-0">{{ $appointmentCount }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="avatar avatar-md rounded-circle bg-info-light">
                                        <i class="bi bi-cart-check text-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-muted mb-0">Orders Completed</p>
                                    <h5 class="mb-0">{{ $orderCount > 0 ? round(($orderCount * 0.75), 0) : 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="avatar avatar-md rounded-circle bg-warning-light">
                                        <i class="bi bi-currency-dollar text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-muted mb-0">Average Order Value</p>
                                    <h5 class="mb-0">RM{{ $orderCount > 0 ? number_format($totalSales / $orderCount, 2) : '0.00' }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="mb-3">Business Growth</h6>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-sm text-muted">Previous Period</span>
                            <span class="text-sm text-success">+6.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Appointment Stats -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Appointment Statistics</h6>
                    <a href="#" class="btn btn-sm btn-primary">View All Appointments</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="text-center p-4 border rounded">
                                <div class="avatar avatar-lg rounded-circle bg-primary-light mx-auto mb-3">
                                    <i class="bi bi-calendar-plus text-primary"></i>
                                </div>
                                <h3 class="mb-1">{{ $appointmentCount }}</h3>
                                <p class="text-muted mb-0">Total Appointments</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="text-center p-4 border rounded">
                                <div class="avatar avatar-lg rounded-circle bg-success-light mx-auto mb-3">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <h3 class="mb-1">{{ round($appointmentCount * 0.8) }}</h3>
                                <p class="text-muted mb-0">Completed</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="text-center p-4 border rounded">
                                <div class="avatar avatar-lg rounded-circle bg-warning-light mx-auto mb-3">
                                    <i class="bi bi-clock text-warning"></i>
                                </div>
                                <h3 class="mb-1">{{ round($appointmentCount * 0.15) }}</h3>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="text-center p-4 border rounded">
                                <div class="avatar avatar-lg rounded-circle bg-danger-light mx-auto mb-3">
                                    <i class="bi bi-x-circle text-danger"></i>
                                </div>
                                <h3 class="mb-1">{{ round($appointmentCount * 0.05) }}</h3>
                                <p class="text-muted mb-0">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    // Filter update function
    function updateFilter(element) {
        // Get current URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        
        // Update the parameter based on the changed element
        urlParams.set(element.name, element.value);
        
        // If period is changed to 'year', remove month parameter
        if (element.name === 'period' && element.value === 'year') {
            urlParams.delete('month');
            document.getElementById('month-selector').style.display = 'none';
        } else if (element.name === 'period' && element.value === 'month') {
            // If period is changed to 'month', add month parameter if not present
            if (!urlParams.has('month')) {
                urlParams.set('month', document.getElementById('month').value);
            }
            document.getElementById('month-selector').style.display = 'block';
        }
        
        // Redirect to the new URL with updated parameters
        window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Parse data from PHP
        const salesData = JSON.parse('{!! $salesData !!}');
        const visitorData = JSON.parse('{!! $visitorData !!}');
        const orderStatusData = JSON.parse('{!! $orderStatusData !!}');
        const paymentStatusData = JSON.parse('{!! $paymentStatusData !!}');
        
        // Sales Performance Chart
        const salesChartCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesChartCtx, {
            type: 'line',
            data: {
                labels: salesData.map(item => item.label),
                datasets: [
                    {
                        label: 'Sales (RM)',
                        data: salesData.map(item => item.value),
                        borderColor: '#6e8efb',
                        backgroundColor: 'rgba(110, 142, 251, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#6e8efb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Visitors',
                        data: visitorData.map(item => item.value),
                        borderColor: '#2DCE89',
                        backgroundColor: 'rgba(45, 206, 137, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2DCE89',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.label === 'Sales (RM)') {
                                    label += 'RM' + context.raw.toFixed(2);
                                } else {
                                    label += context.raw;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        },
                        ticks: {
                            callback: function(value) {
                                return 'RM' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Order Status Chart
        const orderStatusLabels = Object.keys(orderStatusData);
        const orderStatusValues = Object.values(orderStatusData);
        const orderStatusColors = [
            '#ffc107', // pending
            '#0dcaf0', // processing
            '#0d6efd', // shipped
            '#198754', // delivered/completed
            '#dc3545', // cancelled
            '#6c757d'  // other
        ];
        
        const orderStatusChartCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusChartCtx, {
            type: 'doughnut',
            data: {
                labels: orderStatusLabels.map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                datasets: [{
                    data: orderStatusValues,
                    backgroundColor: orderStatusColors.slice(0, orderStatusLabels.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
        
        // Payment Status Chart
        const paymentStatusLabels = Object.keys(paymentStatusData);
        const paymentStatusValues = Object.values(paymentStatusData);
        const paymentStatusColors = [
            '#198754', // paid
            '#ffc107', // pending
            '#dc3545', // failed
            '#6c757d'  // other
        ];
        
        const paymentStatusChartCtx = document.getElementById('paymentStatusChart').getContext('2d');
        const paymentStatusChart = new Chart(paymentStatusChartCtx, {
            type: 'doughnut',
            data: {
                labels: paymentStatusLabels.map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                datasets: [{
                    data: paymentStatusValues,
                    backgroundColor: paymentStatusColors.slice(0, paymentStatusLabels.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection