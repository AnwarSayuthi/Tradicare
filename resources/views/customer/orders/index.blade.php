@extends('layout')

@section('title', 'My Orders - Tradicare')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Orders</h1>
    </div>
    
    <div class="order-tabs mb-4">
        <ul class="nav nav-tabs" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="to-pay-tab" data-bs-toggle="tab" data-bs-target="#to-pay" type="button" role="tab" aria-controls="to-pay" aria-selected="false">To Pay</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="to-ship-tab" data-bs-toggle="tab" data-bs-target="#to-ship" type="button" role="tab" aria-controls="to-ship" aria-selected="false">To Ship</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="to-receive-tab" data-bs-toggle="tab" data-bs-target="#to-receive" type="button" role="tab" aria-controls="to-receive" aria-selected="false">To Receive</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
            </li>
        </ul>
    </div>
    
    <div class="tab-content" id="orderTabsContent">
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-bag-x text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No orders found</h5>
                    <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom">
                        <i class="bi bi-shop me-2"></i> Start Shopping
                    </a>
                </div>
            @else
                @foreach($orders as $order)
                <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="text-muted">Order #{{ $order->order_id }}</span>
                            <span class="mx-2">|</span>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                        </div>
                        <span class="badge {{ getStatusBadgeClass($order->status) }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="more-items text-muted">
                                <small>+ {{ $order->items->count() - 2 }} more items</small>
                            </div>
                        @endif
                        
                        <div class="order-summary mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Total:</span>
                                    <span class="fw-bold ms-2">RM{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                    
                                    @if($order->status === 'processing')
                                        <button class="btn btn-outline-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->order_id }}">
                                            Cancel Order
                                        </button>
                                    @endif
                                    
                                    @if($order->status === 'shipped')
                                        <form action="{{ route('customer.orders.receive', $order->order_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary-custom btn-sm ms-2">
                                                Received
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cancel Order Modal -->
                <div class="modal fade" id="cancelOrderModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="cancelOrderModalLabel{{ $order->order_id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelOrderModalLabel{{ $order->order_id }}">Cancel Order #{{ $order->order_id }}</h5>
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
                @endforeach
                
                {{-- Replace manual pagination with the links() method --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
        
        <div class="tab-pane fade" id="to-pay" role="tabpanel" aria-labelledby="to-pay-tab">
            @php
                // Filter orders for the "To Pay" tab
                $toPayOrders = $allOrders->where('payment.status', 'pending');
            @endphp
            
            @if($toPayOrders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No orders to pay</h5>
                    <p class="text-muted">You don't have any pending payments.</p>
                </div>
            @else
                @foreach($toPayOrders as $order)
                    {{-- Order Card Structure --}}
                    <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                        {{-- ... card header ... --}}
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="text-muted">Order #{{ $order->order_id }}</span>
                                <span class="mx-2">|</span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                            </div>
                            <span class="badge bg-warning text-dark">Payment Pending</span>
                        </div>
                        {{-- ... card body ... --}}
                        <div class="card-body">
                            @foreach($order->orderItems->take(2) as $item)
                            <div class="order-item d-flex align-items-center mb-3">
                                <div class="order-item-img me-3">
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                                </div>
                                <div class="order-item-details flex-grow-1">
                                    <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($order->orderItems->count() > 2)
                                <div class="more-items text-muted">
                                    <small>+ {{ $order->orderItems->count() - 2 }} more items</small>
                                </div>
                            @endif
                            
                            <div class="order-summary mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted">Total:</span>
                                        <span class="fw-bold ms-2">RM{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                        <a href="{{ route('customer.orders.pay', $order->order_id) }}" class="btn btn-primary-custom btn-sm ms-2">
                                            Pay Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Add pagination for this tab if needed, passing the filtered collection --}}
                {{-- Note: Standard pagination might not work well directly on filtered collections within tabs. 
                     Consider loading data via AJAX or passing separate paginated collections for each tab from the controller. 
                     For simplicity, I'm omitting pagination here, assuming the main $orders variable handles 'All' tab pagination. --}}
            @endif
        </div>
        
        {{-- Repeat similar structure for other tabs (To Ship, To Receive, Completed, Cancelled) --}}
        {{-- Remember to filter the $allOrders collection for each tab and potentially handle pagination separately if required. --}}

        <div class="tab-pane fade" id="to-ship" role="tabpanel" aria-labelledby="to-ship-tab">
             @php
                // Filter orders for the "To Ship" tab
                $toShipOrders = $allOrders->where('status', 'processing')->where('payment.status', 'paid');
            @endphp
            {{-- ... content for To Ship tab ... --}}
             @if($toShipOrders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No orders to ship</h5>
                    <p class="text-muted">You don't have any orders waiting to be shipped.</p>
                </div>
            @else
                @foreach($toShipOrders as $order)
                {{-- Similar card structure as above --}}
                 <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                     {{-- ... card header ... --}}
                     <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                         <div>
                             <span class="text-muted">Order #{{ $order->order_id }}</span>
                             <span class="mx-2">|</span>
                             <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                         </div>
                         <span class="badge badge-processing">{{ ucfirst($order->status) }}</span> {{-- Use helper if defined --}}
                     </div>
                     {{-- ... card body ... --}}
                     <div class="card-body">
                         @foreach($order->orderItems->take(2) as $item)
                         <div class="order-item d-flex align-items-center mb-3">
                             <div class="order-item-img me-3">
                                 <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                             </div>
                             <div class="order-item-details flex-grow-1">
                                 <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                 <div class="d-flex justify-content-between align-items-center">
                                     <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                     <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                 </div>
                             </div>
                         </div>
                         @endforeach
                         
                         @if($order->orderItems->count() > 2)
                             <div class="more-items text-muted">
                                 <small>+ {{ $order->orderItems->count() - 2 }} more items</small>
                             </div>
                         @endif
                         
                         <div class="order-summary mt-3 pt-3 border-top">
                             <div class="d-flex justify-content-between align-items-center">
                                 <div>
                                     <span class="text-muted">Total:</span>
                                     <span class="fw-bold ms-2">RM{{ number_format($order->total_amount, 2) }}</span>
                                 </div>
                                 <div>
                                     <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                         View Details
                                     </a>
                                     <button class="btn btn-outline-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->order_id }}">
                                         Cancel Order
                                     </button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 {{-- Include Cancel Modal for each order in this tab as well --}}
                 @include('customer.orders._cancel_modal', ['order' => $order]) 
                @endforeach
                 {{-- Pagination for 'To Ship' if needed --}}
            @endif
        </div>

        <div class="tab-pane fade" id="to-receive" role="tabpanel" aria-labelledby="to-receive-tab">
             @php
                // Filter orders for the "To Receive" tab
                $toReceiveOrders = $allOrders->where('status', 'shipped');
            @endphp
            {{-- ... content for To Receive tab ... --}}
             @if($toReceiveOrders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-truck text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No orders to receive</h5>
                    <p class="text-muted">You don't have any orders in transit.</p>
                </div>
            @else
                @foreach($toReceiveOrders as $order)
                {{-- Similar card structure as above --}}
                 <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                     {{-- ... card header ... --}}
                     <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                         <div>
                             <span class="text-muted">Order #{{ $order->order_id }}</span>
                             <span class="mx-2">|</span>
                             <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                         </div>
                         <span class="badge badge-shipped">{{ ucfirst($order->status) }}</span> {{-- Use helper if defined --}}
                     </div>
                     {{-- ... card body ... --}}
                     <div class="card-body">
                         @foreach($order->orderItems->take(2) as $item)
                         <div class="order-item d-flex align-items-center mb-3">
                             <div class="order-item-img me-3">
                                 <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                             </div>
                             <div class="order-item-details flex-grow-1">
                                 <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                 <div class="d-flex justify-content-between align-items-center">
                                     <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                     <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                 </div>
                             </div>
                         </div>
                         @endforeach
                         
                         @if($order->orderItems->count() > 2)
                             <div class="more-items text-muted">
                                 <small>+ {{ $order->orderItems->count() - 2 }} more items</small>
                             </div>
                         @endif
                         
                         <div class="order-summary mt-3 pt-3 border-top">
                             <div class="d-flex justify-content-between align-items-center">
                                 <div>
                                     <span class="text-muted">Total:</span>
                                     <span class="fw-bold ms-2">RM{{ number_format($order->total_amount, 2) }}</span>
                                 </div>
                                 <div>
                                     <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                         View Details
                                     </a>
                                     <form action="{{ route('customer.orders.receive', $order->order_id) }}" method="POST" class="d-inline">
                                         @csrf
                                         <button type="submit" class="btn btn-primary-custom btn-sm ms-2">
                                             Received
                                         </button>
                                     </form>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                @endforeach
                 {{-- Pagination for 'To Receive' if needed --}}
            @endif
        </div>

        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
             @php
                // Filter orders for the "Completed" tab
                $completedOrders = $allOrders->where('status', 'completed');
            @endphp
            {{-- ... content for Completed tab ... --}}
             @if($completedOrders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No completed orders</h5>
                    <p class="text-muted">You don't have any completed orders yet.</p>
                </div>
            @else
                @foreach($completedOrders as $order)
                {{-- Similar card structure as above --}}
                 <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                     {{-- ... card header ... --}}
                     <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                         <div>
                             <span class="text-muted">Order #{{ $order->order_id }}</span>
                             <span class="mx-2">|</span>
                             <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                         </div>
                         <span class="badge badge-completed">{{ ucfirst($order->status) }}</span> {{-- Use helper if defined --}}
                     </div>
                     {{-- ... card body ... --}}
                     <div class="card-body">
                         @foreach($order->orderItems->take(2) as $item)
                         <div class="order-item d-flex align-items-center mb-3">
                             <div class="order-item-img me-3">
                                 <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                             </div>
                             <div class="order-item-details flex-grow-1">
                                 <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                 <div class="d-flex justify-content-between align-items-center">
                                     <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                     <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                 </div>
                             </div>
                         </div>
                         @endforeach
                         
                         @if($order->orderItems->count() > 2)
                             <div class="more-items text-muted">
                                 <small>+ {{ $order->orderItems->count() - 2 }} more items</small>
                             </div>
                         @endif
                         
                         <div class="order-summary mt-3 pt-3 border-top">
                             <div class="d-flex justify-content-between align-items-center">
                                 <div>
                                     <span class="text-muted">Total:</span>
                                     <span class="fw-bold ms-2">RM{{ number_format($order->total_amount, 2) }}</span>
                                 </div>
                                 <div>
                                     <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                         View Details
                                     </a>
                                     {{-- Add Re-order or Review button if applicable --}}
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                @endforeach
                 {{-- Pagination for 'Completed' if needed --}}
            @endif
        </div>

        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
             @php
                // Filter orders for the "Cancelled" tab
                $cancelledOrders = $allOrders->where('status', 'cancelled');
            @endphp
            {{-- ... content for Cancelled tab ... --}}
             @if($cancelledOrders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-x-circle text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No cancelled orders</h5>
                    <p class="text-muted">You don't have any cancelled orders.</p>
                </div>
            @else
                @foreach($cancelledOrders as $order)
                {{-- Similar card structure as above --}}
                 <div class="card shadow-sm border-0 rounded-lg mb-4 order-card bg-light"> {{-- Added bg-light for visual distinction --}}
                     {{-- ... card header ... --}}
                     <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                         <div>
                             <span class="text-muted">Order #{{ $order->order_id }}</span>
                             <span class="mx-2">|</span>
                             <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                         </div>
                         <span class="badge badge-cancelled">{{ ucfirst($order->status) }}</span> {{-- Use helper if defined --}}
                     </div>
                     {{-- ... card body ... --}}
                     <div class="card-body">
                         @foreach($order->orderItems->take(2) as $item)
                         <div class="order-item d-flex align-items-center mb-3">
                             <div class="order-item-img me-3">
                                 <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded" style="opacity: 0.7;"> {{-- Slightly faded image --}}
                             </div>
                             <div class="order-item-details flex-grow-1">
                                 <h6 class="mb-0 text-muted">{{ $item->product->product_name }}</h6> {{-- Muted text --}}
                                 <div class="d-flex justify-content-between align-items-center">
                                     <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                     <span class="fw-medium text-muted">${{ number_format($item->unit_price, 2) }}</span> {{-- Muted price --}}
                                 </div>
                             </div>
                         </div>
                         @endforeach
                         
                         @if($order->orderItems->count() > 2)
                             <div class="more-items text-muted">
                                 <small>+ {{ $order->orderItems->count() - 2 }} more items</small>
                             </div>
                         @endif
                         
                         <div class="order-summary mt-3 pt-3 border-top">
                             <div class="d-flex justify-content-between align-items-center">
                                 <div>
                                     <span class="text-muted">Total:</span>
                                     <span class="fw-bold ms-2 text-muted">${{ number_format($order->total_amount, 2) }}</span> {{-- Muted total --}}
                                 </div>
                                 <div>
                                     <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-secondary btn-sm"> {{-- Secondary outline --}}
                                         View Details
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                @endforeach
                 {{-- Pagination for 'Cancelled' if needed --}}
            @endif
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Order Styling */
    .order-card {
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
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
    
    .more-items {
        font-style: italic;
    }
    
    /* Tab Styling */
    .nav-tabs {
        border-bottom: 1px solid rgba(73, 54, 40, 0.2);
    }
    
    .nav-tabs .nav-link {
        color: #555;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        background: transparent;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: var(--primary);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary);
        background-color: transparent;
        border-bottom: 2px solid var(--primary);
    }
    
    /* Badge Styling */
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        font-size: 0.75rem;
        border-radius: 50px; /* Pill shape */
    }
    
    .badge-processing {
        background-color: #eef2ff; /* Light Indigo */
        color: #4338ca; /* Indigo */
    }
    
    .badge-shipped {
        background-color: #fffbeb; /* Light Amber */
        color: #b45309; /* Amber */
    }
    
    .badge-completed {
        background-color: #f0fdf4; /* Light Green */
        color: #15803d; /* Green */
    }
    
    .badge-cancelled {
        background-color: #fef2f2; /* Light Red */
        color: #b91c1c; /* Red */
    }

    .badge-pending { /* For To Pay tab */
        background-color: #fffbeb; /* Light Amber */
        color: #b45309; /* Amber */
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .order-summary .d-flex { /* Target the flex container inside summary */
            flex-direction: column;
            align-items: flex-start !important; /* Override alignment */
            gap: 0.5rem; /* Add gap between total and buttons */
        }
         .order-summary .d-flex > div:last-child { /* Target the button container */
            margin-top: 0.5rem; /* Add space above buttons */
            width: 100%; /* Make buttons take full width */
            display: flex;
            justify-content: flex-end; /* Align buttons right */
        }
    }
    
    @media (max-width: 575.98px) {
        .nav-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .nav-tabs::-webkit-scrollbar {
            display: none;
        }
        
        .nav-tabs .nav-item {
            float: none;
        }
         .order-summary .d-flex > div:last-child { /* Target the button container */
             justify-content: center; /* Center buttons on very small screens */
         }
         .order-summary .d-flex > div:last-child .btn { /* Target buttons */
             flex-grow: 1; /* Make buttons expand */
             text-align: center;
         }
         .order-summary .d-flex > div:last-child .btn:first-child {
             margin-right: 0.5rem; /* Add space between buttons */
         }
    }

    /* Pagination Styling - Added */
    .pagination {
        --bs-pagination-color: var(--primary);
        --bs-pagination-hover-color: var(--primary-light);
        --bs-pagination-focus-color: var(--primary-light);
        --bs-pagination-active-bg: var(--primary);
        --bs-pagination-active-border-color: var(--primary);
        --bs-pagination-border-radius: 0.375rem; /* Match button radius */
        --bs-pagination-hover-bg: #f8f9fa;
    }

    .page-item:not(:first-child) .page-link {
        margin-left: 0.25rem; /* Add small gap between page numbers */
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
        
        // Make the helper function available to the blade template
        window.getStatusBadgeClass = getStatusBadgeClass;
        
        // Get URL parameters to activate the correct tab
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        
        if (status) {
            const tabEl = document.querySelector(`#${status}-tab`);
            if (tabEl) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }
    });
</script>
@endsection

