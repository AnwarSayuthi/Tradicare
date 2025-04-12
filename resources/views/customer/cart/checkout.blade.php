@extends('layout')

@section('title', 'Checkout - Tradicare')

@section('content')
<div class="container-lg container-fluid py-5">
    <div class="row">

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h1 class="h3 mb-0 checkout-title">Checkout</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.place.order') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-4">
                            <h4 class="mb-3 section-heading">Shipping Information</h4>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-check-circle me-2"></i> Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-lg mb-4 sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h4 mb-0">Order Summary</h2>
                </div>
                <div class="card-body">
                    <div class="order-items mb-4">
                        @foreach($cartItems as $item)
                        <div class="order-item d-flex align-items-center mb-3">
                            <div class="order-item-img me-3">
                                <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    <span class="fw-medium">${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <div class="order-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>$5.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>${{ number_format($totalPrice + 5, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Secure checkout message -->
                    <div class="secure-checkout mt-4 text-center">
                        <i class="bi bi-shield-lock text-success me-2"></i>
                        <small class="text-muted">Secure checkout. Your data is protected.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Checkout Styling */
    .checkout-title {
        font-weight: 600;
        color: var(--primary);
    }
    
    .section-heading {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
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
    
    .payment-methods {
        padding: 1.25rem;
        border: 1px solid rgba(73, 54, 40, 0.1);
        border-radius: 10px;
        background-color: #f9f9f9;
    }
    
    .form-check-label {
        cursor: pointer;
        font-weight: 500;
    }
    
    .payment-icon {
        font-size: 1.2rem;
        color: var(--primary);
    }
    
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .secure-checkout {
        font-size: 0.85rem;
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .col-lg-4 {
            margin-top: 2rem;
        }
    }
</style>
@endsection