<div class="tab-content" id="orderTabsContent">
    <!-- All Orders Tab -->
    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
        @if(isset($allOrders) && $allOrders->isEmpty())
            <div class="text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="bi bi-bag-x text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mt-3">No orders found</h5>
                <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom">
                    <i class="bi bi-shop me-2"></i> Start Shopping
                </a>
            </div>
        @elseif(isset($allOrders))
            @foreach($allOrders as $order)
            <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                    <div class="order-header-info mb-2 mb-md-0">
                        <span class="order-id">Order #{{ $order->order_id }}</span>
                        <span class="mx-2 d-none d-md-inline">|</span>
                        <span class="order-date text-muted d-block d-md-inline">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                    </div>
                    <span class="badge badge-{{ $order->status }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                @if($item->product->product_image)
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->product_name }}" 
                                         class="img-fluid rounded product-image"
                                         loading="lazy">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="product-name mb-1">{{ $item->product->product_name }}</h6>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <small class="text-muted quantity-info mb-1 mb-sm-0">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium price-info">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-muted small mb-3">
                                +{{ $order->items->count() - 2 }} more items
                            </div>
                        @endif
                    </div>
                    
                    <div class="order-summary d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-3 border-top">
                        <div class="order-total mb-2 mb-sm-0">
                            <span class="text-muted">Total: </span>
                            <span class="fw-bold text-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="order-actions d-flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                            
                            @if($order->status === 'processing')
                                <button class="btn btn-outline-danger btn-sm" onclick="openCancelOrderModal({{ $order->order_id }})">
                                    <i class="bi bi-x-circle me-1"></i>Cancel Order
                                </button>
                            @elseif($order->status === 'shipped')
                                <button class="btn btn-success btn-sm" onclick="markAsReceived({{ $order->order_id }})">
                                    <i class="bi bi-check-circle me-1"></i>Received
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    
    <!-- To Pay Tab -->
    <div class="tab-pane fade" id="to-pay" role="tabpanel" aria-labelledby="to-pay-tab">
        @php
            $toPayOrders = isset($allOrders) ? $allOrders->filter(function($order) {
                return $order->payments->where('status', 'pending')->isNotEmpty() || $order->payments->isEmpty();
            }) : collect();
        @endphp
        
        @if($toPayOrders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No orders to pay</h5>
                <p class="text-muted">You don't have any pending payments.</p>
            </div>
        @else
            @foreach($toPayOrders as $order)
            <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                    <div class="order-header-info mb-2 mb-md-0">
                        <span class="order-id">Order #{{ $order->order_id }}</span>
                        <span class="mx-2 d-none d-md-inline">|</span>
                        <span class="order-date text-muted d-block d-md-inline">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                    </div>
                    <span class="badge badge-{{ $order->status }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                @if($item->product->product_image)
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->product_name }}" 
                                         class="img-fluid rounded product-image"
                                         loading="lazy">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="product-name mb-1">{{ $item->product->product_name }}</h6>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <small class="text-muted quantity-info mb-1 mb-sm-0">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium price-info">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-muted small mb-3">
                                +{{ $order->items->count() - 2 }} more items
                            </div>
                        @endif
                    </div>
                    
                    <div class="order-summary d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-3 border-top">
                        <div class="order-total mb-2 mb-sm-0">
                            <span class="text-muted">Total: </span>
                            <span class="fw-bold text-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="order-actions d-flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                            <button class="btn btn-primary btn-sm">
                                <i class="bi bi-credit-card me-1"></i>Pay Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    
    <!-- To Ship Tab -->
    <div class="tab-pane fade" id="to-ship" role="tabpanel" aria-labelledby="to-ship-tab">
        @php
            $toShipOrders = isset($allOrders) ? $allOrders->where('status', 'processing') : collect();
        @endphp
        
        @if($toShipOrders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No orders to ship</h5>
                <p class="text-muted">You don't have any orders waiting to be shipped.</p>
            </div>
        @else
            @foreach($toShipOrders as $order)
            <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                    <div class="order-header-info mb-2 mb-md-0">
                        <span class="order-id">Order #{{ $order->order_id }}</span>
                        <span class="mx-2 d-none d-md-inline">|</span>
                        <span class="order-date text-muted d-block d-md-inline">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                    </div>
                    <span class="badge badge-{{ $order->status }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                @if($item->product->product_image)
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->product_name }}" 
                                         class="img-fluid rounded product-image"
                                         loading="lazy">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="product-name mb-1">{{ $item->product->product_name }}</h6>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <small class="text-muted quantity-info mb-1 mb-sm-0">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium price-info">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-muted small mb-3">
                                +{{ $order->items->count() - 2 }} more items
                            </div>
                        @endif
                    </div>
                    
                    <div class="order-summary d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-3 border-top">
                        <div class="order-total mb-2 mb-sm-0">
                            <span class="text-muted">Total: </span>
                            <span class="fw-bold text-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="order-actions d-flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                            <!-- Replace the existing cancel button with this -->
                            @if($order->status === 'processing')
                                <button class="btn btn-outline-danger btn-sm" onclick="openCancelOrderModal({{ $order->order_id }})">
                                    <i class="bi bi-x-circle me-1"></i>Cancel Order
                                </button>
                            @elseif($order->status === 'shipped')
                                <button class="btn btn-success btn-sm" onclick="markAsReceived({{ $order->order_id }})">
                                    <i class="bi bi-check-circle me-1"></i>Received
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    
    <!-- To Receive Tab -->
    <div class="tab-pane fade" id="to-receive" role="tabpanel" aria-labelledby="to-receive-tab">
        @php
            $toReceiveOrders = isset($allOrders) ? $allOrders->where('status', 'shipped') : collect();
        @endphp
        
        @if($toReceiveOrders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-truck text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No orders to receive</h5>
                <p class="text-muted">You don't have any orders in transit.</p>
            </div>
        @else
            @foreach($toReceiveOrders as $order)
            <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                    <div class="order-header-info mb-2 mb-md-0">
                        <span class="order-id">Order #{{ $order->order_id }}</span>
                        <span class="mx-2 d-none d-md-inline">|</span>
                        <span class="order-date text-muted d-block d-md-inline">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                    </div>
                    <span class="badge badge-{{ $order->status }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                @if($item->product->product_image)
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->product_name }}" 
                                         class="img-fluid rounded product-image"
                                         loading="lazy">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="product-name mb-1">{{ $item->product->product_name }}</h6>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <small class="text-muted quantity-info mb-1 mb-sm-0">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium price-info">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-muted small mb-3">
                                +{{ $order->items->count() - 2 }} more items
                            </div>
                        @endif
                    </div>
                    
                    <div class="order-summary d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-3 border-top">
                        <div class="order-total mb-2 mb-sm-0">
                            <span class="text-muted">Total: </span>
                            <span class="fw-bold text-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="order-actions d-flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                            <button class="btn btn-success btn-sm" onclick="markAsReceived({{ $order->order_id }})">
                                <i class="bi bi-check-circle me-1"></i>Received
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    
    <!-- Completed Tab -->
    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
        @php
            $completedOrders = isset($allOrders) ? $allOrders->where('status', 'delivered') : collect();
        @endphp
        
        @if($completedOrders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No completed orders</h5>
                <p class="text-muted">You don't have any completed orders.</p>
            </div>
        @else
            @foreach($completedOrders as $order)
            <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                    <div class="order-header-info mb-2 mb-md-0">
                        <span class="order-id">Order #{{ $order->order_id }}</span>
                        <span class="mx-2 d-none d-md-inline">|</span>
                        <span class="order-date text-muted d-block d-md-inline">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                    </div>
                    <span class="badge badge-{{ $order->status }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                @if($item->product->product_image)
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->product_name }}" 
                                         class="img-fluid rounded product-image"
                                         loading="lazy">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="product-name mb-1">{{ $item->product->product_name }}</h6>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <small class="text-muted quantity-info mb-1 mb-sm-0">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium price-info">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-muted small mb-3">
                                +{{ $order->items->count() - 2 }} more items
                            </div>
                        @endif
                    </div>
                    
                    <div class="order-summary d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-3 border-top">
                        <div class="order-total mb-2 mb-sm-0">
                            <span class="text-muted">Total: </span>
                            <span class="fw-bold text-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="order-actions d-flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    
    <!-- Cancelled Tab -->
    <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
        @php
            $cancelledOrders = isset($allOrders) ? $allOrders->where('status', 'cancelled') : collect();
        @endphp
        
        @if($cancelledOrders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-x-circle text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No cancelled orders</h5>
                <p class="text-muted">You don't have any cancelled orders.</p>
            </div>
        @else
            @foreach($cancelledOrders as $order)
            <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                    <div class="order-header-info mb-2 mb-md-0">
                        <span class="order-id">Order #{{ $order->order_id }}</span>
                        <span class="mx-2 d-none d-md-inline">|</span>
                        <span class="order-date text-muted d-block d-md-inline">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                    </div>
                    <span class="badge badge-{{ $order->status }} order-status">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                @if($item->product->product_image)
                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->product_name }}" 
                                         class="img-fluid rounded product-image"
                                         loading="lazy">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="product-name mb-1">{{ $item->product->product_name }}</h6>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <small class="text-muted quantity-info mb-1 mb-sm-0">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium price-info">RM{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                            <div class="text-muted small mb-3">
                                +{{ $order->items->count() - 2 }} more items
                            </div>
                        @endif
                    </div>
                    
                    <div class="order-summary d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-3 border-top">
                        <div class="order-total mb-2 mb-sm-0">
                            <span class="text-muted">Total: </span>
                            <span class="fw-bold text-primary">RM{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="order-actions d-flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
<!-- Add this at the end of the file -->
<script>
function openCancelOrderModal(orderId) {
    // Set the order ID in the modal button
    document.getElementById('confirmCancelOrder').setAttribute('data-order-id', orderId);
    
    // Open the modal
    const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
    modal.show();
}
</script>