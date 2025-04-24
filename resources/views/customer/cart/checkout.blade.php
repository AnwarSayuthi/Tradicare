@extends('layout')

@section('title', 'Checkout - Tradicare')

@section('content')
<div class="container-lg container-fluid py-5">
    <div class="row">

        <div class="col-lg-4 order-1 order-lg-1 mb-4 mb-lg-0">
            <div class="card shadow-sm border-0 rounded-lg mb-4 sticky-lg-top" style="top: 20px; z-index: 100;">
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

        <div class="col-lg-8 order-2 order-lg-2">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h1 class="h3 mb-0 checkout-title">Checkout</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.place.order') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-4">
                            <!-- Shipping Address Selection -->
                            <div class="shipping-address-container mb-4 p-3 border rounded bg-light">
                                @if(isset($selectedAddress))
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                                <strong>{{ $selectedAddress->location_name }}</strong>
                                                @if($selectedAddress->is_default)
                                                    <span class="badge bg-danger ms-2">Default</span>
                                                @endif
                                            </div>
                                            <p class="mb-1">{{ $selectedAddress->address_line1 }}</p>
                                            @if($selectedAddress->address_line2)
                                                <p class="mb-1">{{ $selectedAddress->address_line2 }}</p>
                                            @endif
                                            <p class="mb-1">{{ $selectedAddress->city }}, {{ $selectedAddress->state }} {{ $selectedAddress->postal_code }}</p>
                                            @if($selectedAddress->phone_number)
                                                <p class="mb-0">{{ $selectedAddress->phone_number }}</p>
                                            @endif
                                            <input type="hidden" name="shipping_address" value="{{ $selectedAddress->location_id }}">
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addressSelectionModal">
                                            Change
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bi bi-geo-alt-fill text-muted fs-1"></i>
                                        <p class="mb-2">No shipping address selected</p>
                                        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addressSelectionModal">
                                            Select Address
                                        </button>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <h4 class="mb-3 section-heading">Payment Method</h4>
                                <div class="payment-methods">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cash_on_delivery" checked>
                                        <label class="form-check-label d-flex align-items-center" for="payment_cod">
                                            <i class="bi bi-cash-coin payment-icon me-2"></i>
                                            Cash on Delivery
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_credit" value="credit_card">
                                        <label class="form-check-label d-flex align-items-center" for="payment_credit">
                                            <i class="bi bi-credit-card payment-icon me-2"></i>
                                            Credit/Debit Card
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal">
                                        <label class="form-check-label d-flex align-items-center" for="payment_paypal">
                                            <i class="bi bi-paypal payment-icon me-2"></i>
                                            PayPal
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-primary-custom" id="place-order-btn">
                                <i class="bi bi-check-circle me-2"></i> Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Address Selection Modal remains unchanged -->
<div class="modal fade" id="addressSelectionModal" tabindex="-1" aria-labelledby="addressSelectionModalLabel" aria-hidden="true">
    <!-- Modal content remains the same -->
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
    
    /* Address Selection Styling */
    .address-card {
        transition: all 0.2s ease;
    }
    
    .address-radio:checked + label .address-card {
        border-color: var(--primary) !important;
        background-color: rgba(var(--primary-rgb), 0.05);
    }
    
    .address-radio {
        margin-top: 1.5rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .sticky-lg-top {
            position: relative;
            top: 0 !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Address selection handling
        const addressRadios = document.querySelectorAll('.address-radio');
        const addressCards = document.querySelectorAll('.address-card');
        
        addressRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                addressCards.forEach(card => {
                    card.classList.remove('border-primary');
                });
                
                if (this.checked) {
                    this.closest('.form-check').querySelector('.address-card').classList.add('border-primary');
                }
            });
        });
        
        // Add new address button
        const addNewAddressBtn = document.getElementById('add-new-address-btn');
        const addressFormContainer = document.getElementById('address-form-container');
        const addressFormTitle = document.getElementById('address-form-title');
        const addressForm = document.getElementById('address-form');
        const cancelAddressBtn = document.getElementById('cancel-address-btn');
        
        addNewAddressBtn.addEventListener('click', function() {
            // Reset form
            addressForm.reset();
            document.getElementById('location_id').value = '';
            addressFormTitle.textContent = 'Add New Address';
            
            // Show form
            addressFormContainer.classList.remove('d-none');
            this.classList.add('d-none');
        });
        
        cancelAddressBtn.addEventListener('click', function() {
            addressFormContainer.classList.add('d-none');
            addNewAddressBtn.classList.remove('d-none');
        });
        
        // Edit address buttons
        const editAddressBtns = document.querySelectorAll('.edit-address-btn');
        
        editAddressBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const locationId = this.getAttribute('data-location-id');
                
                // Here you would typically fetch the address data from the server
                // For now, we'll simulate it with a placeholder
                // In a real implementation, you would make an AJAX call to get the address details
                
                // Show form with edit title
                addressFormTitle.textContent = 'Edit Address';
                document.getElementById('location_id').value = locationId;
                
                // Show form and hide add button
                addressFormContainer.classList.remove('d-none');
                addNewAddressBtn.classList.add('d-none');
                
                // Populate form with address data (this would be done with actual data in a real implementation)
                // This is just a placeholder - you would need to implement the actual data fetching
                fetch(`/customer/profile/location/${locationId}/get`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const location = data.location;
                        document.getElementById('location_name').value = location.location_name;
                        document.getElementById('phone_number').value = location.phone_number || '';
                        document.getElementById('address_line1').value = location.address_line1;
                        document.getElementById('address_line2').value = location.address_line2 || '';
                        document.getElementById('city').value = location.city;
                        document.getElementById('state').value = location.state;
                        document.getElementById('postal_code').value = location.postal_code;
                        document.getElementById('is_default').checked = location.is_default;
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                });
            });
        });
        
        // Form submission
        addressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const locationId = document.getElementById('location_id').value;
            const isNewAddress = !locationId;
            
            // Determine URL based on whether this is a new address or an edit
            const url = isNewAddress 
                ? '/customer/profile/location/add' 
                : `/customer/profile/location/${locationId}/update`;
            
            const method = isNewAddress ? 'POST' : 'PUT';
            
            // Submit form data via AJAX
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show the updated address list
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
        
        // Use selected address button
        const useSelectedAddressBtn = document.getElementById('use-selected-address');
        
        useSelectedAddressBtn.addEventListener('click', function() {
            const selectedAddressRadio = document.querySelector('input[name="address_selection"]:checked');
            
            if (selectedAddressRadio) {
                const locationId = selectedAddressRadio.value;
                
                // Update the hidden input in the main form
                document.querySelector('input[name="shipping_address"]').value = locationId;
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addressSelectionModal'));
                modal.hide();
                
                // Refresh the page or update the displayed address via AJAX
                window.location.reload();
            } else {
                alert('Please select an address or add a new one.');
            }
        });
        
        // Validate checkout form before submission
        const checkoutForm = document.getElementById('checkout-form');
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        checkoutForm.addEventListener('submit', function(e) {
            const shippingAddressInput = document.querySelector('input[name="shipping_address"]');
            
            if (!shippingAddressInput.value) {
                e.preventDefault();
                alert('Please select a shipping address before placing your order.');
                
                // Open the address selection modal
                const addressModal = new bootstrap.Modal(document.getElementById('addressSelectionModal'));
                addressModal.show();
            }
        });
    });
</script>
@endsection