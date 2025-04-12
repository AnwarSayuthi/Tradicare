@extends('layout')

@section('title', 'Your Cart - Tradicare')

@section('content')
<div class="container-lg container-fluid py-5">
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
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end pe-4">Subtotal</th>
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
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
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
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <span class="me-3">${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                                <form action="{{ route('customer.cart.remove', $item->cart_item_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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
                                            <span class="fw-medium">${{ number_format($item->unit_price, 2) }}</span>
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
                                            <span class="fw-medium">Subtotal: ${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <form action="{{ route('customer.cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-2"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm border-0 rounded-lg sticky-top" style="top: 20px; z-index: 100;">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <!-- In the order summary section, remove the discount section -->
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>$5.00</span>
                        </div>
                        
                        <!-- Discount section removed -->
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">${{ number_format($totalPrice + 5, 2) }}</span>
                        </div>
                        <a href="{{ route('customer.checkout') }}" class="btn btn-primary-custom w-100">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
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

@section('css')
<style>
    /* Cart Styling */
    .cart-title {
        font-weight: 600;
        color: var(--primary);
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
    
    .discount-section {
        font-size: 0.85rem;
        background-color: #f9f9f9;
        cursor: pointer;
    }
    
    .discount-section i.bi-chevron-right {
        font-size: 0.75rem;
        color: #aaa;
    }
    
    /* Sticky summary for mobile */
    @media (max-width: 991.98px) {
        .sticky-top {
            position: sticky;
            top: 0;
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .cart-item-mobile {
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
    }
</style>
@endsection