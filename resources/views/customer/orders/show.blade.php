@extends('layout')

@section('title', 'Order Details - Tradicare')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.profile') }}?button=order">My Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">Order #{{ $order->order_id }}</h1>
                        <span class="badge" data-status="{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <p class="text-muted mb-0">Placed on {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="order-timeline mb-4">
                        <div class="timeline-track">
                            <div class="timeline-progress" data-status="{{ $order->status }}"></div>
                        </div>
                        <div class="timeline-steps">
                            <div class="timeline-step {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'active' : '' }}">
                                <div class="timeline-step-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="timeline-step-label">Order Placed</div>
                            </div>
                            <div class="timeline-step {{ in_array($order->status, ['shipped', 'completed']) ? 'active' : '' }}">
                                <div class="timeline-step-icon">
                                    <i class="bi bi-box-seam-fill"></i>
                                </div>
                                <div class="timeline-step-label">Shipped</div>
                            </div>
                            <div class="timeline-step {{ $order->status === 'completed' ? 'active' : '' }}">
                                <div class="timeline-step-icon">
                                    <i class="bi bi-house-door-fill"></i>
                                </div>
                                <div class="timeline-step-label">Delivered</div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Items in Your Order</h4>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->product_image)
                                                <img src="{{ $item->product->getImageUrl() }}" alt="{{ $item->product->product_name }}" class="img-thumbnail me-3" width="60">
                                            @else
                                                <div class="placeholder-image me-3">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                                <small class="text-muted">{{ ucfirst($item->product->category) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><span class="fw-bold">RM{{ number_format($item->unit_price, 2) }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end">RM{{ number_format($order->total_amount - 5, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Shipping:</td>
                                    <td class="text-end">RM5.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">RM{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h4 mb-0">Order Information</h2>
                </div>
                <div class="card-body">
                    <div class="info-section mb-4">
                        <h5 class="info-title">Shipping Address</h5>
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    </div>
                    
                    <div class="info-section mb-4">
                        <h5 class="info-title">Payment Information</h5>
                        @if($order->payments && $order->payments->count() > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Method:</span>
                                <span>{{ ucfirst(str_replace('_', ' ', $order->payments->first()->payment_method)) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Status:</span>
                                <span class="badge {{ $order->payments->first()->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($order->payments->first()->status) }}
                                </span>
                            </div>
                            @if($order->payments->first()->transaction_id)
                            <div class="d-flex justify-content-between">
                                <span>Transaction ID:</span>
                                <span>{{ $order->payments->first()->transaction_id }}</span>
                            </div>
                            @endif
                        @else
                            <div class="alert alert-info">No payment information available</div>
                        @endif
                    </div>
                    
                    <div class="order-actions">
                        @if($order->status === 'processing')
                            <button class="btn btn-outline-danger w-100 mb-3" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                Cancel Order
                            </button>
                        @endif
                        
                        @if($order->status === 'shipped')
                            <form action="{{ route('customer.orders.receive', $order->order_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary-custom w-100 mb-3">
                                    Confirm Delivery
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status === 'completed')
                            <a href="{{ route('customer.reviews.create', ['product_id' => $order->orderItems->first()->product_id]) }}" class="btn btn-outline-primary w-100 mb-3">
                                Write a Review
                            </a>
                        @endif
                        
                        <a href="{{ route('customer.profile') }}?button=order" class="btn btn-outline-primary w-100 mb-3">
                            Return to Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order #{{ $order->order_id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="{{ route('customer.orders.cancel', $order->order_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Compact Order Details Styling */
    .container {
        max-width: 1200px;
    }
    
    /* Card Enhancements - Reduced Padding */
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
        padding: 1rem;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #495057 0%, #343a40 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.3px;
        padding: 0.75rem 0.5rem;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table thead th:first-child {
        border-top-left-radius: 6px;
    }
    
    .table thead th:last-child {
        border-top-right-radius: 6px;
    }
    
    .table tbody td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
        background: white;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .table tfoot td {
        padding: 0.75rem 0.5rem;
        font-weight: 600;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-top: 2px solid #dee2e6;
    }
    
    .table tfoot tr:last-child td {
        border-bottom: none;
        font-size: 1rem;
        color: #495057;
    }
    
    .table tfoot tr:last-child td:first-child {
        border-bottom-left-radius: 6px;
    }
    
    .table tfoot tr:last-child td:last-child {
        border-bottom-right-radius: 6px;
    }
    
    /* Product Image Styling - Smaller */
    .order-item-img {
        width: 45px;
        height: 45px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }
    
    .order-item-img:hover {
        transform: scale(1.05);
    }
    
    .order-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .placeholder-image {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.2rem;
    }
    
    /* Product Details - Compact */
    .table tbody td h6 {
        margin-bottom: 0.15rem;
        font-weight: 600;
        color: #212529;
        font-size: 0.9rem;
    }
    
    .table tbody td small {
        color: #6c757d;
        font-size: 0.75rem;
        text-transform: capitalize;
    }
    
    /* Price and Quantity Styling */
    .table tbody td .fw-bold {
        color: #28a745;
        font-size: 1rem;
        font-weight: 700;
    }
    
    .table tbody td:nth-child(2) {
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
        color: #495057;
    }
    
    /* Info Section Styling - Compact */
    .info-title {
        font-size: 1rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
        position: relative;
    }
    
    .info-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 40px;
        height: 2px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    
    .info-section {
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        margin-bottom: 1rem;
        border-left: 3px solid #007bff;
    }
    
    .info-section p {
        margin-bottom: 0;
        color: #495057;
        line-height: 1.5;
        font-size: 0.9rem;
    }
    
    .info-section .d-flex {
        margin-bottom: 0.5rem;
    }
    
    .info-section .d-flex:last-child {
        margin-bottom: 0;
    }
    
    .info-section .d-flex span:first-child {
        font-weight: 600;
        color: #495057;
        min-width: 100px;
        font-size: 0.85rem;
    }
    
    .info-section .d-flex span:last-child {
        color: #212529;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    /* Badge Styling */
    .badge {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 15px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #000000; /* Black text color for all badges */
    }
    
    /* Status Badge Colors with black text */
    .badge-processing {
        background-color: #f8f9fa;
        color: #000000;
        border: 1px solid #dee2e6;
    }
    
    .badge-shipped {
        background-color: #e3f2fd;
        color: #000000;
        border: 1px solid #bbdefb;
    }
    
    .badge-completed {
        background-color: #e8f5e9;
        color: #000000;
        border: 1px solid #c8e6c9;
    }
    
    .badge-cancelled {
        background-color: #ffebee;
        color: #000000;
        border: 1px solid #ffcdd2;
    }
    
    /* Order Timeline - Compact */
    .order-timeline {
        padding: 1.5rem 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        margin: 1.5rem 0;
    }
    
    .timeline-track {
        height: 4px;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        position: relative;
        margin: 0 auto 1.5rem;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .timeline-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        transition: width 0.8s ease;
        border-radius: 4px;
    }
    
    .timeline-steps {
        display: flex;
        justify-content: space-between;
        padding: 0 1.5rem;
    }
    
    .timeline-step {
        text-align: center;
        flex: 1;
        position: relative;
    }
    
    .timeline-step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        color: #adb5bd;
        transition: all 0.4s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 1rem;
    }
    
    .timeline-step.active .timeline-step-icon {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        transform: scale(1.05);
        box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3);
    }
    
    .timeline-step-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .timeline-step.active .timeline-step-label {
        color: #28a745;
        font-weight: 700;
    }
    
    /* Button Styling - Compact */
    .order-actions .btn {
        border-radius: 20px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        font-size: 0.85rem;
    }
    
    .order-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    }
    
    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }
    
    .btn-outline-danger:hover {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-color: #dc3545;
        color: white;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        color: white;
    }
    
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    }
    
    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }
    
    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border-color: #007bff;
        color: white;
    }
    
    /* Breadcrumb Styling - Compact */
    .breadcrumb {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .breadcrumb-item a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .col-lg-4 {
            margin-top: 1.5rem;
        }
        
        .timeline-steps {
            padding: 0 1rem;
        }
        
        .timeline-step-icon {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .timeline-step-label {
            font-size: 0.7rem;
        }
        
        .timeline-step-icon {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .table thead th,
        .table tbody td,
        .table tfoot td {
            padding: 0.5rem 0.25rem;
        }
        
        .order-actions .btn {
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
        }
        
        .info-section {
            padding: 0.75rem;
        }
        
        .order-timeline {
            padding: 1rem 0;
            margin: 1rem 0;
        }
    }
    
    /* Animation for smooth transitions */
    * {
        transition: all 0.2s ease;
    }
    
    /* Focus states for accessibility */
    .btn:focus,
    .table tbody tr:focus {
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function for badge classes
        function getStatusBadgeClass(status) {
            switch(status) {
                case 'processing':
                    return 'badge-processing';
                case 'shipped':
                    return 'badge-shipped';
                case 'completed':
                    return 'badge-completed';
                case 'cancelled':
                    return 'badge-cancelled';
                default:
                    return 'bg-secondary';
            }
        }
        
        // Helper function for order progress percentage
        function getOrderProgressPercentage(status) {
            switch(status) {
                case 'processing':
                    return 33;
                case 'shipped':
                    return 66;
                case 'completed':
                    return 100;
                case 'cancelled':
                    return 0;
                default:
                    return 0;
            }
        }
        
        // Make the helper functions available to the blade template
        window.getStatusBadgeClass = getStatusBadgeClass;
        window.getOrderProgressPercentage = getOrderProgressPercentage;
    });
</script>
@endsection