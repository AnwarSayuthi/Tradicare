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
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->order_id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>RM{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment->status == 'completed' ? 'success' : 'danger' }}">
                                    {{ $order->payment->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Replace the existing pagination with this new one -->
            {{-- <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div> --}}
            @if($orders->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
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
    .badge-processing { background-color: #0dcaf0; color: #000; }
    .badge-shipped { background-color: #0d6efd; color: #fff; }
    .badge-delivered { background-color: #198754; color: #fff; }
    .badge-cancelled { background-color: #dc3545; color: #fff; }
    .table th, .table td { vertical-align: middle; }
    .dropdown-toggle::after { display: none; } /* Hide default dropdown arrow */
</style>
@endpush