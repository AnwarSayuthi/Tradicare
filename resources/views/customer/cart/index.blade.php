@extends('layout')

@section('title', 'Your Cart - Tradicare')

@section('content')
<div class="container-lg container-fluid py-5">
    @php
        // Calculate total if it's not already defined
        if (!isset($total) && isset($cart) && $cart->cartItems->count() > 0) {
            $total = 0;
            foreach($cart->cartItems as $item) {
                $total += $item->unit_price * $item->quantity;
            }
        }
    @endphp
    
    <h1 class="mb-4 cart-title">Your Shopping Cart</h1>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($cart && $cart->cartItems->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 product-col">Product</th>
                                        <th class="price-col text-center">Price</th>
                                        <th class="quantity-col text-center">Quantity</th>
                                        <th class="subtotal-col text-center pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->cartItems as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="cart-item-img me-3">
                                                    <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                                    <small class="text-muted">{{ ucfirst($item->product->category) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">RM{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="quantity-selector cart-quantity">
                                                    <form action="{{ route('customer.cart.decrement', $item->cart_item_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="quantity-btn minus-btn">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('customer.cart.update', $item->cart_item_id) }}" method="POST" class="d-inline quantity-form">
                                                        @csrf
                                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="quantity-input" onchange="this.form.submit()">
                                                    </form>
                                                    
                                                    <form action="{{ route('customer.cart.increment', $item->cart_item_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="quantity-btn plus-btn">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <span class="me-3">RM{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                                <form action="{{ route('customer.cart.remove', $item->cart_item_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile view for cart items -->
                        <div class="d-md-none">
                            @foreach($cart->cartItems as $item)
                            <div class="cart-item-mobile p-3 border-bottom">
                                <div class="d-flex mb-3">
                                    <div class="cart-item-img me-3">
                                        <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-1">{{ $item->product->product_name }}</h6>
                                            <form action="{{ route('customer.cart.remove', $item->cart_item_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <small class="text-muted d-block mb-2">{{ ucfirst($item->product->category) }}</small>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                            <div class="quantity-selector cart-quantity-sm">
                                                <form action="{{ route('customer.cart.decrement', $item->cart_item_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="quantity-btn minus-btn">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('customer.cart.update', $item->cart_item_id) }}" method="POST" class="d-inline quantity-form">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="quantity-input" onchange="this.form.submit()">
                                                </form>
                                                
                                                <form action="{{ route('customer.cart.increment', $item->cart_item_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="quantity-btn plus-btn">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="text-end mt-2">
                                            <span class="fw-medium">Subtotal: RM{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Cart Total and Checkout Button -->
                    <div class="card-footer bg-white py-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="cart-total">
                                    <span class="text-muted me-2">Total:</span>
                                    <span class="fw-bold fs-5">RM{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end cart-actions">
                                    <form action="{{ route('customer.cart.clear') }}" method="POST" class="d-grid d-md-block">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger py-2 px-4">
                                            <i class="bi bi-trash me-2"></i> Clear Cart
                                        </button>
                                    </form>
                                    <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary py-2 px-4">
                                        <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                                    </a>
                                    <button type="button" class="btn btn-primary-custom checkout-btn py-2 px-4" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                        <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Clear Cart Button - Removed from here and moved to card footer -->
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-4">Your Cart is Empty</h3>
                <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom mt-3">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

<!-- Add this checkout modal at the end of your content section -->
<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title" id="checkoutModalLabel">Complete Your Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Order Summary -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="order-summary-container">
                            <h6 class="fw-bold mb-3">Order Summary</h6>
                            <div class="order-items mb-3" style="max-height: 250px; overflow-y: auto;">
                                @foreach($cart->cartItems as $item)
                                <div class="order-item d-flex align-items-center mb-2 pb-2 border-bottom">
                                    <div class="order-item-img me-2">
                                        <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div class="order-item-details flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0 small fw-medium">{{ $item->product->product_name }}</p>
                                            <p class="mb-0 small">RM{{ number_format($item->unit_price * $item->quantity, 2) }}</span></p>
                                        </div>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="d-flex justify-content-between fw-bold mt-3 pt-2 border-top">
                                <span>Total</span>
                                <span>RM{{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div class="col-md-7">
                        <form action="{{ route('customer.place.order') }}" method="POST" id="checkout-form">
                            @csrf
                            <h6 class="fw-bold mb-3">Shipping Address</h6>
                            
                            <div class="shipping-address-container mb-4 p-3 border rounded bg-light">
                                <div id="address-selection-area">
                                    @if(auth()->user()->locations()->exists())
                                        @php
                                            $defaultAddress = auth()->user()->locations()->where('is_default', true)->first();
                                            if(!$defaultAddress) {
                                                $defaultAddress = auth()->user()->locations()->first();
                                            }
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                                </div>
                                                <strong>{{ $defaultAddress->location_name }}</strong>
                                                <p class="mb-1 small">{{ $defaultAddress->address_line1 }}</p>
                                                @if($defaultAddress->address_line2)
                                                    <p class="mb-1 small">{{ $defaultAddress->address_line2 }}</p>
                                                @endif
                                                <p class="mb-1 small">{{ $defaultAddress->city }}, {{ $defaultAddress->state }} {{ $defaultAddress->postal_code }}</p>
                                                <input type="hidden" name="shipping_address" value="{{ $defaultAddress->location_id }}">
                                            </div>
                                            <div class="d-flex flex-column align-items-end gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="change-address-btn">
                                                    Change
                                                </button>
                                                @if($defaultAddress->is_default)
                                                    <span class="badge bg-danger">Default</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="bi bi-geo-alt-fill text-muted fs-4"></i>
                                            <p class="mb-2 small">No shipping address found</p>
                                            <button type="button" class="btn btn-sm btn-primary-custom" id="add-address-btn">
                                                Add Address
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                
                                <div id="address-form-container" class="d-none">
                                    <div class="mb-3">
                                        <label for="location_name" class="form-label small">Address Name</label>
                                        <input type="text" class="form-control form-control-sm" id="location_name" name="location_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address_line1" class="form-label small">Address Line 1</label>
                                        <input type="text" class="form-control form-control-sm" id="address_line1" name="address_line1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address_line2" class="form-label small">Address Line 2 (Optional)</label>
                                        <input type="text" class="form-control form-control-sm" id="address_line2" name="address_line2">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="city" class="form-label small">City</label>
                                            <input type="text" class="form-control form-control-sm" id="city" name="city" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="state" class="form-label small">State</label>
                                            <input type="text" class="form-control form-control-sm" id="state" name="state" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label small">Postal Code</label>
                                        <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label small">Phone Number</label>
                                        <input type="tel" class="form-control form-control-sm" id="phone_number" name="phone_number">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                        <label class="form-check-label small" for="is_default">
                                            Set as default address
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="cancel-address-btn">Cancel</button>
                                        <button type="button" class="btn btn-sm btn-primary-custom" id="save-address-btn">Save Address</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary-custom py-2" id="place-order-btn">
                                    <i class="bi bi-check-circle me-2"></i> Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Address form handling
        const changeAddressBtn = document.getElementById('change-address-btn');
        const addAddressBtn = document.getElementById('add-address-btn');
        const cancelAddressBtn = document.getElementById('cancel-address-btn');
        const saveAddressBtn = document.getElementById('save-address-btn');
        const addressSelectionArea = document.getElementById('address-selection-area');
        const addressFormContainer = document.getElementById('address-form-container');
        
        if (changeAddressBtn) {
            changeAddressBtn.addEventListener('click', function() {
                addressSelectionArea.classList.add('d-none');
                addressFormContainer.classList.remove('d-none');
            });
        }
        
        if (addAddressBtn) {
            addAddressBtn.addEventListener('click', function() {
                addressSelectionArea.classList.add('d-none');
                addressFormContainer.classList.remove('d-none');
            });
        }
        
        if (cancelAddressBtn) {
            cancelAddressBtn.addEventListener('click', function() {
                addressFormContainer.classList.add('d-none');
                addressSelectionArea.classList.remove('d-none');
            });
        }
        
        if (saveAddressBtn) {
            saveAddressBtn.addEventListener('click', function() {
                // Get form data
                const formData = new FormData();
                formData.append('location_name', document.getElementById('location_name').value);
                formData.append('address_line1', document.getElementById('address_line1').value);
                formData.append('address_line2', document.getElementById('address_line2').value || '');
                formData.append('city', document.getElementById('city').value);
                formData.append('state', document.getElementById('state').value);
                formData.append('postal_code', document.getElementById('postal_code').value);
                formData.append('phone_number', document.getElementById('phone_number').value || '');
                formData.append('is_default', document.getElementById('is_default').checked ? 1 : 0);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Send AJAX request to save address
                fetch('{{ route("customer.location.add") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show the new address
                        window.location.reload();
                    } else {
                        alert('There was an error saving your address. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error saving your address. Please try again.');
                });
            });
        }
        
        // Validate checkout form before submission
        const checkoutForm = document.getElementById('checkout-form');
        
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const shippingAddressInput = document.querySelector('input[name="shipping_address"]');
                
                if (!shippingAddressInput || !shippingAddressInput.value) {
                    e.preventDefault();
                    alert('Please add a shipping address before placing your order.');
                    
                    if (addressSelectionArea) {
                        addressSelectionArea.classList.add('d-none');
                    }
                    
                    if (addressFormContainer) {
                        addressFormContainer.classList.remove('d-none');
                    }
                }
            });
        }
    });
</script>
@endsection

@section('css')
<style>
    /* Cart Styling */
    .cart-title {
        font-weight: 600;
        color: var(--primary);
    }
    
    /* Table column widths */
    .product-col {
        width: 50%;
    }
    
    .price-col, .quantity-col, .subtotal-col {
        width: 16.66%;
    }
    
    /* Center align table headers */
    .table th {
        font-weight: 600;
        padding: 1rem;
    }
    
    /* Center align table cells */
    .table td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
    }
    
    /* Cart actions styling */
    .cart-actions {
        gap: 10px;
    }
    
    .cart-actions .btn {
        min-width: 160px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        padding: 10px 20px;
        height: 46px;
    }
    
    @media (max-width: 767.98px) {
        .cart-actions {
            gap: 12px;
        }
        
        .cart-actions .btn {
            width: 100%;
        }
    }
    .cart-item-img {
        width: 60px;
        height: 60px;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }
    
    .quantity-btn {
        background: #f8f9fa;
        border: none;
        padding: 0.25rem 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .quantity-btn:hover {
        background: #e9ecef;
    }
    
    .quantity-input {
        width: 40px;
        border: none;
        text-align: center;
        font-weight: 500;
        padding: 0.25rem 0;
    }
    
    .quantity-input:focus {
        outline: none;
    }
    
    /* Remove spinner from number input */
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .quantity-input[type=number] {
        -moz-appearance: textfield;
    }
    
    .cart-item-mobile {
        background-color: white;
    }
    
    .cart-quantity-sm {
        transform: scale(0.9);
    }
    
    /* Checkout button styling */
    .checkout-btn {
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .cart-total {
        font-size: 1.1rem;
    }
    
    .card-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    .cart-actions .btn {
        min-width: 160px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .cart-actions .btn:hover {
        transform: translateY(-1px);
    }
    
    .cart-actions .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .cart-actions .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .cart-item-mobile {
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .card-footer {
            text-align: center;
            padding: 1.5rem;
        }
        
        .cart-total {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
        }
        
        .cart-actions .btn {
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
        }
    }
    
    /* Checkout Modal Styling */
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1.25rem 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: var(--primary);
    }
    
    .order-item-img {
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 6px;
    }
    
    .order-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .shipping-address-container {
        border-radius: 8px;
    }
    
    #place-order-btn {
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    #place-order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Form styling */
    .form-control-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
    }
    
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(73, 54, 40, 0.25);
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .order-summary-container {
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
    }
</style>
@endsection