@extends('admin_layout')

@section('title', 'Manage Orders - Tradicare Admin')

@php
// Define the helper function in PHP
function getStatusBadgeClass($status) {
    switch($status) {
        case 'processing':
            return 'badge-processing';
        case 'shipped':
            return 'badge-shipped';
        case 'delivered':
            return 'badge-delivered';
        case 'cancelled':
            return 'badge-cancelled';
        default:
            return 'bg-secondary';
    }
}
@endphp

@section('content')
<style>
/* Enhanced CSS for better appearance */
.order-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.order-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.order-table thead th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-transform: uppercase;
    font-size: 0.8rem;
}

.order-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.order-table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid transparent;
}

.badge-processing {
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    color: white;
    border-color: #74b9ff;
}

.badge-shipped {
    background: linear-gradient(135deg, #a29bfe, #6c5ce7);
    color: white;
    border-color: #a29bfe;
}

.badge-delivered {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    border-color: #00b894;
}

.badge-cancelled {
    background: linear-gradient(135deg, #fd79a8, #e84393);
    color: white;
    border-color: #fd79a8;
}

.payment-badge {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 2px solid transparent;
}

.payment-pending {
    background: linear-gradient(135deg, #fdcb6e, #e17055);
    color: white;
    border-color: #fdcb6e;
}

.payment-complete {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    border-color: #00b894;
}

.btn-action {
    border-radius: 8px;
    padding: 8px 12px;
    margin: 0 2px;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
    background: white;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    background: #f7fafc;
    border-color: #cbd5e0;
}

/* Enhanced stats cards layout - 5 cards in one row */
.stats-container {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.25rem;
    margin-bottom: 2.5rem;
    padding: 0 0.5rem;
}

.stats-card {
    border-radius: 16px;
    border: none;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    height: 100%;
    min-width: 0; /* Prevents overflow */
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.stats-card .card-body {
    padding: 1.5rem 1rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    height: 100%;
}

.stats-card .icon-container {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-right: 1rem;
    flex-shrink: 0;
}

.stats-card h6 {
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
}

.stats-card h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
    margin: 0;
}

/* Enhanced filter section - Full width optimized layout */
.filter-card {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.filter-card .card-body {
    padding: 1.75rem 2.5rem;
}

.filter-card .row {
    align-items: end;
    gap: 0;
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}

.filter-card .col-12,
.filter-card .col-md-6,
.filter-card .col-lg-3,
.filter-card .col-lg-4,
.filter-card .col-lg-2,
.filter-card .col-md-4,
.filter-card .col-md-8 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.filter-card .form-floating {
    margin-bottom: 0;
}

.filter-card .form-floating > .form-control,
.filter-card .form-floating > .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    background: white;
    height: 56px;
    font-size: 0.95rem;
    padding: 1rem 0.75rem 0.25rem 0.75rem;
}

.filter-card .form-floating > label {
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.filter-card .form-floating > .form-control:focus,
.filter-card .form-floating > .form-select:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
}

.filter-card .form-floating > .form-control:focus ~ label,
.filter-card .form-floating > .form-select:focus ~ label,
.filter-card .form-floating > .form-control:not(:placeholder-shown) ~ label,
.filter-card .form-floating > .form-select:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    color: #667eea;
}

.filter-card .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    height: 56px;
    font-size: 0.95rem;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    min-width: 120px;
}

.filter-card .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
}

.filter-card .btn-primary i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

/* Full width layout for large screens */
@media (min-width: 1200px) {
    .filter-card .card-body {
        padding: 2rem 3rem;
    }
    
    .filter-card .col-lg-3 {
        flex: 0 0 auto;
        width: 20%;
    }
    
    .filter-card .col-lg-4 {
        flex: 0 0 auto;
        width: 45%;
    }
    
    .filter-card .col-lg-2 {
        flex: 0 0 auto;
        width: 15%;
    }
}

/* Optimized layout for desktop screens */
@media (min-width: 992px) and (max-width: 1199.98px) {
    .filter-card .row {
        align-items: center;
    }
    
    .filter-card .col-lg-3 {
        flex: 0 0 auto;
        width: 22%;
    }
    
    .filter-card .col-lg-4 {
        flex: 0 0 auto;
        width: 40%;
    }
    
    .filter-card .col-lg-2 {
        flex: 0 0 auto;
        width: 18%;
    }
    
    .filter-card .d-flex.align-items-center {
        height: 56px;
        justify-content: center;
    }
}

/* Tablet responsive */
@media (max-width: 991.98px) {
    .filter-card .card-body {
        padding: 1.5rem 2rem;
    }
    
    .filter-card .row {
        row-gap: 1rem;
    }
    
    .filter-card .col-md-6,
    .filter-card .col-md-8,
    .filter-card .col-md-4 {
        margin-bottom: 0;
    }
}

/* Mobile responsive */
@media (max-width: 767.98px) {
    .filter-card .card-body {
        padding: 1.25rem 1.5rem;
    }
    
    .filter-card .row {
        row-gap: 1rem;
    }
    
    .filter-card .col-12 {
        margin-bottom: 0;
    }
    
    .filter-card .btn-primary {
        width: 100%;
        margin-top: 0;
    }
}

@media (max-width: 575.98px) {
    .filter-card .card-body {
        padding: 1rem 1.25rem;
    }
    
    .filter-card .col-12 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
}

/* Additional optimizations for full width */
.filter-card .d-flex.align-items-center {
    min-height: 56px;
}

.filter-card .form-floating > .form-control::placeholder,
.filter-card .form-floating > .form-select::placeholder {
    color: transparent;
}

.filter-card .form-floating > .form-control:focus::placeholder,
.filter-card .form-floating > .form-select:focus::placeholder {
    color: #6c757d;
    opacity: 0.6;
}

/* Responsive design for 5 cards */
@media (max-width: 1400px) {
    .stats-container {
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }
    
    .stats-card .card-body {
        padding: 1.25rem 0.75rem;
    }
    
    .stats-card .icon-container {
        width: 55px;
        height: 55px;
        margin-right: 0.75rem;
    }
    
    .stats-card h6 {
        font-size: 0.75rem;
    }
    
    .stats-card h2 {
        font-size: 1.5rem;
    }
}

@media (max-width: 1200px) {
    .stats-container {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .stats-card:nth-child(4),
    .stats-card:nth-child(5) {
        grid-column: span 1;
    }
    
    .stats-card:nth-child(4) {
        grid-column: 1 / 3;
        justify-self: center;
        max-width: 300px;
    }
    
    .stats-card:nth-child(5) {
        grid-column: 3;
        justify-self: center;
        max-width: 300px;
    }
}

@media (max-width: 992px) {
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 0;
    }
    
    .stats-card:nth-child(5) {
        grid-column: 1 / 3;
        justify-self: center;
        max-width: 300px;
    }
    
    .stats-card .card-body {
        padding: 1.25rem 1rem;
    }
    
    .stats-card .icon-container {
        width: 60px;
        height: 60px;
        margin-right: 1rem;
    }
    
    .stats-card h6 {
        font-size: 0.8rem;
    }
    
    .stats-card h2 {
        font-size: 1.75rem;
    }
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stats-card:nth-child(4),
    .stats-card:nth-child(5) {
        grid-column: 1;
        justify-self: stretch;
        max-width: none;
    }
    
    .stats-card .card-body {
        padding: 1.25rem 1rem;
        flex-direction: row;
        text-align: left;
    }
    
    .stats-card .icon-container {
        width: 60px;
        height: 60px;
        margin-right: 1rem;
    }
    
    .filter-card .card-body {
        padding: 1.5rem;
    }
}

@media (max-width: 576px) {
    .stats-card .card-body {
        padding: 1rem 0.75rem;
    }
    
    .stats-card .icon-container {
        width: 55px;
        height: 55px;
        margin-right: 0.75rem;
    }
    
    .stats-card h6 {
        font-size: 0.75rem;
    }
    
    .stats-card h2 {
        font-size: 1.5rem;
    }
    
    .filter-card .card-body {
        padding: 1.25rem;
    }
}

/* Color coding for all 5 card types */
.stats-card:nth-child(1) .icon-container {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
}

.stats-card:nth-child(1) .icon-container i {
    color: #1976d2;
}

.stats-card:nth-child(2) .icon-container {
    background: linear-gradient(135deg, #fff3e0, #ffcc02);
}

.stats-card:nth-child(2) .icon-container i {
    color: #f57c00;
}

.stats-card:nth-child(3) .icon-container {
    background: linear-gradient(135deg, #f3e5f5, #ce93d8);
}

.stats-card:nth-child(3) .icon-container i {
    color: #8e24aa;
}

.stats-card:nth-child(4) .icon-container {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
}

.stats-card:nth-child(4) .icon-container i {
    color: #388e3c;
}

.stats-card:nth-child(5) .icon-container {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
}

.stats-card:nth-child(5) .icon-container i {
    color: #d32f2f;
}

/* Additional visual enhancements */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: 1px solid #e2e8f0;
    color: #667eea;
}

.pagination .page-link:hover {
    background-color: #f7fafc;
    border-color: #cbd5e0;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
}

/* Empty state styling */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state h5 {
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #718096;
    margin-bottom: 0;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Order Management</h1>
    </div>
    
    <!-- Order Statistics -->
    <div class="stats-container">
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="bi bi-cart-check fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalOrders }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="bi bi-clock fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Processing</h6>
                        <h2 class="mb-0 fw-bold">{{ $processingOrders }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Shipped</h6>
                        <h2 class="mb-0 fw-bold">{{ $shippedOrders }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Delivered</h6>
                        <h2 class="mb-0 fw-bold">{{ $deliveredOrders }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="bi bi-x-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Cancelled</h6>
                        <h2 class="mb-0 fw-bold">{{ $cancelledOrders }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Orders Card -->
    <div class="card main-card border-0">
        <div class="card-header bg-white p-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-1 fw-bold text-primary">Manage Orders</h4>
                <p class="text-muted mb-0">Track and manage customer orders and payments</p>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card-body p-4">
            <div class="card filter-card shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-floating">
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Select date range" value="{{ request('date_range') }}">
                                <label for="date_range">Date Range</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-lg-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Order ID, Customer name or email" value="{{ request('search') }}">
                                <label for="search">Search by Order ID, Customer name or email</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-2 d-flex align-items-center">
                            <button type="submit" class="btn btn-primary w-100 py-3">
                                <i class="bi bi-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 order-table">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-4">Order ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="order-id">#{{ $order->order_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle text-white me-3">
                                        {{ substr($order->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="customer-name">{{ $order->user->name }}</h6>
                                        <small class="customer-email">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="amount-text">RM{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $paymentStatus = $order->payments->first() ? $order->payments->first()->status : 'pending';
                                    $badgeClass = $paymentStatus == 'completed' ? 'payment-complete' : 'payment-pending';
                                    $displayStatus = $paymentStatus == 'completed' ? 'Complete' : 'Pending';
                                @endphp
                                <span class="payment-badge {{ $badgeClass }}">
                                    <i class="bi {{ $paymentStatus == 'completed' ? 'bi-check-circle' : 'bi-clock' }} me-1"></i>
                                    {{ $displayStatus }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $orderStatus = $order->status ?? 'processing';
                                    $statusBadgeClass = match($orderStatus) {
                                        'processing' => 'badge-processing',
                                        'shipped' => 'badge-shipped', 
                                        'delivered' => 'badge-delivered',
                                        'cancelled' => 'badge-cancelled',
                                        default => 'badge-processing'
                                    };
                                    $statusIcon = match($orderStatus) {
                                        'processing' => 'bi-clock',
                                        'shipped' => 'bi-truck',
                                        'delivered' => 'bi-check-circle',
                                        'cancelled' => 'bi-x-circle',
                                        default => 'bi-clock'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusBadgeClass }}">
                                    <i class="bi {{ $statusIcon }} me-1"></i>
                                    {{ ucfirst($orderStatus) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="btn btn-action" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-cart-x"></i>
                                    <h5 class="fw-medium mb-1">No Orders Found</h5>
                                    <p class="text-muted mb-0">There are no orders matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($orders->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "M d, Y",
    });

    // Handle Cancel Order Reason Submission
    document.querySelectorAll('[id^="cancelOrderModal"]').forEach(modal => {
        const form = modal.querySelector('form[action*="update-status"]');
        const reasonTextarea = modal.querySelector('textarea[name="cancel_reason"]');
        const reasonInput = form.querySelector('input[name="notes"]');
        const submitButton = form.querySelector('button[type="submit"]');

        submitButton.addEventListener('click', function(event) {
            // Check if the textarea is empty
            if (reasonTextarea.value.trim() === '') {
                event.preventDefault(); // Stop form submission
                reasonTextarea.classList.add('is-invalid'); // Add validation error class
                // Optionally, add an error message display
                let errorDiv = reasonTextarea.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    reasonTextarea.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Please provide a reason for cancellation.';
            } else {
                reasonTextarea.classList.remove('is-invalid'); // Remove error class if valid
                reasonInput.value = reasonTextarea.value; // Copy reason to hidden input
            }
        });

        // Remove error state when user starts typing
        reasonTextarea.addEventListener('input', function() {
            if (reasonTextarea.value.trim() !== '') {
                reasonTextarea.classList.remove('is-invalid');
                let errorDiv = reasonTextarea.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.textContent = ''; // Clear error message
                }
            }
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .text-purple { color: #6c5ce7 !important; }
    .table th, .table td { vertical-align: middle; }
    .dropdown-toggle::after { display: none; } /* Hide default dropdown arrow */

/* Status Badge Styles */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    white-space: nowrap;
}

.status-processing {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #f59e0b;
}

.status-shipped {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 1px solid #3b82f6;
}

.status-delivered {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
    border: 1px solid #22c55e;
}

.status-cancelled {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
    border: 1px solid #ef4444;
}

.status-badge i {
    font-size: 0.75rem;
}
    .table th, .table td { vertical-align: middle; }
    .dropdown-toggle::after { display: none; } /* Hide default dropdown arrow */
</style>
@endpush