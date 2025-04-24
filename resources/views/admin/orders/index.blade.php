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
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Order Management</h1>
        <div>
            <a href="{{ route('admin.reports.generate', 'orders') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> Generate Report
            </a>
        </div>
    </div>
    
    <!-- Order Statistics -->
    <div class="row mb-4">
        <div class="col-6 col-md-4 col-lg-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Orders</h6>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Processing</h6>
                    <h3 class="mb-0">{{ $processingOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Shipped</h6>
                    <h3 class="mb-0">{{ $shippedOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Delivered</h6>
                    <h3 class="mb-0">{{ $deliveredOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Cancelled</h6>
                    <h3 class="mb-0">{{ $cancelledOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Revenue</h6>
                    <h3 class="mb-0">RM {{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="date_range" class="form-label">Date Range</label>
                    <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Select date range" value="{{ request('date_range') }}">
                </div>
                <div class="col-12 col-md-8 col-lg-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Order ID, Customer name or email" value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-4 col-lg-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th class="d-none d-md-table-cell">Date</th>
                            <th class="d-none d-md-table-cell">Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="d-none d-lg-table-cell">Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-4">No orders found</td>
                            </tr>
                        @else
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->order_id) }}" class="fw-medium text-decoration-none">
                                            #{{ $order->order_id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $order->user->name }}</div>
                                        <small class="text-muted d-none d-md-inline">{{ $order->user->email }}</small>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</td>
                                    <td class="d-none d-md-table-cell">{{ $order->orderItems->count() }}</td>
                                    <td>RM {{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ getStatusBadgeClass($order->status) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($order->payment)
                                            <span class="badge {{ $order->payment->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ucfirst($order->payment->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">No Payment</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="orderActionDropdown{{ $order->order_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="d-none d-md-inline">Actions</span>
                                                <i class="bi bi-three-dots d-md-none"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="orderActionDropdown{{ $order->order_id }}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.orders.show', $order->order_id) }}">
                                                        <i class="bi bi-eye me-2"></i> View Details
                                                    </a>
                                                </li>
                                                @if($order->status === 'processing')
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->order_id }}">
                                                            <i class="bi bi-truck me-2"></i> Mark as Shipped
                                                        </button>
                                                    </li>
                                                @endif
                                                @if($order->status === 'shipped')
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->order_id }}">
                                                            <i class="bi bi-check-circle me-2"></i> Mark as Delivered
                                                        </button>
                                                    </li>
                                                @endif
                                                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                                                    <li>
                                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->order_id }}">
                                                            <i class="bi bi-x-circle me-2"></i> Cancel Order
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        
                                        <!-- Update Status Modal -->
                                        <div class="modal fade" id="updateStatusModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $order->order_id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateStatusModalLabel{{ $order->order_id }}">Update Order Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.orders.update-status', $order->order_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="status{{ $order->order_id }}" class="form-label">New Status</label>
                                                                <select class="form-select" id="status{{ $order->order_id }}" name="status">
                                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="notes{{ $order->order_id }}" class="form-label">Notes (Optional)</label>
                                                                <textarea class="form-control" id="notes{{ $order->order_id }}" name="notes" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Cancel Order Modal -->
                                        <div class="modal fade" id="cancelOrderModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="cancelOrderModalLabel{{ $order->order_id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelOrderModalLabel{{ $order->order_id }}">Cancel Order #{{ $order->order_id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                                                        <form id="cancelForm{{ $order->order_id }}">
                                                            <div class="mb-3">
                                                                <label for="cancelReason{{ $order->order_id }}" class="form-label">Reason for Cancellation</label>
                                                                <textarea class="form-control" id="cancelReason{{ $order->order_id }}" name="cancel_reason" rows="3" required></textarea>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <form action="{{ route('admin.orders.update-status', $order->order_id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <input type="hidden" id="cancelReasonInput{{ $order->order_id }}" name="notes">
                                                            <button type="submit" class="btn btn-danger" onclick="document.getElementById('cancelReasonInput{{ $order->order_id }}').value = document.getElementById('cancelReason{{ $order->order_id }}').value">Cancel Order</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<style>
    /* Badge Styling */
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    .badge-processing {
        background-color: #3498db;
        color: white;
    }
    
    .badge-shipped {
        background-color: #f39c12;
        color: white;
    }
    
    .badge-delivered {
        background-color: #2ecc71;
        color: white;
    }
    
    .badge-cancelled {
        background-color: #e74c3c;
        color: white;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .table th, .table td {
            padding: 0.75rem 0.5rem;
        }
        
        .badge {
            padding: 0.4rem 0.6rem;
            font-size: 0.7rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .dropdown-menu {
            min-width: 10rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem;
        }
        
        h3 {
            font-size: 1.5rem;
        }
        
        .table th, .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.875rem;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date range picker
        $('#date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
        
        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        
        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
@endsection