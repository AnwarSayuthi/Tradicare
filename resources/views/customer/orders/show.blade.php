@extends('layout')

@section('title', 'Order Details - Tradicare')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">Order #{{ $order->order_id }}</h1>
                        <span class="badge {{ getStatusBadgeClass($order->status) }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <p class="text-muted mb-0">Placed on {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="order-timeline mb-4">
                        <div class="timeline-track">
                            <div class="timeline-progress" style="width: {{ getOrderProgressPercentage($order->status) }}%"></div>
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
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
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
                                    <span class="fw-bold">RM{{ number_format($item->unit_price, 2) }}</span>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end">${{ number_format($order->total_amount - 5, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Shipping:</td>
                                    <td class="text-end">$5.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">RM{{ number_format($order->total_amount, 2) }}</span></td>
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
                        <div class="d-flex justify-content-between mb-2">
                            <span>Method:</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Status:</span>
                            <span class="badge {{ $order->payment->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        </div>
                        @if($order->payment->transaction_id)
                        <div class="d-flex justify-content-between">
                            <span>Transaction ID:</span>
                            <span>{{ $order->payment->transaction_id }}</span>
                        </div>
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
                        
                        <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary w-100">
                            Back to Orders
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
    /* Order Details Styling */
    .order-item-img {
        width: 60px;
        height: 60px;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .order-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .info-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(73, 54, 40, 0.1);
    }
    
    /* Order Timeline */
    .order-timeline {
        padding: 2rem 0;
    }
    
    .timeline-track {
        height: 4px;
        background-color: #e9ecef;
        position: relative;
        margin: 0 auto 1.5rem;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .timeline-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: var(--primary);
        transition: width 0.5s ease;
    }
    
    .timeline-steps {
        display: flex;
        justify-content: space-between;
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
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        color: #adb5bd;
        transition: all 0.3s ease;
    }
    
    .timeline-step.active .timeline-step-icon {
        background: var(--primary);
        color: white;
    }
    
    .timeline-step-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .timeline-step.active .timeline-step-label {
        color: var(--primary);
        font-weight: 600;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .col-lg-4 {
            margin-top: 2rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .timeline-step-label {
            font-size: 0.75rem;
        }
        
        .timeline-step-icon {
            width: 32px;
            height: 32px;
            font-size: 0.85rem;
        }
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