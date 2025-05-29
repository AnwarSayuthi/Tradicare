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
                        <!-- Desktop view for cart items -->
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
                                    <button type="button" class="btn btn-primary-custom checkout-btn py-2 px-4 pulse-animation" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                        <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
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
                            
                            <!-- Order Summary section -->
                            <div class="order-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small">Subtotal (Products)</span>
                                    <span class="text-end small">RM{{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small">Shipping</span>
                                    <span class="text-end small">RM{{ isset($shippingCost) ? number_format($shippingCost, 2) : '5.00' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small">Service Tax</span>
                                    <span class="text-end small">RM1.00</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total Payment</span>
                                    <span class="text-end fw-bold">RM{{ number_format($totalPrice + (isset($shippingCost) ? $shippingCost : 5) + 1, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div class="col-md-7">
                        <!-- Inside your checkout form, add a hidden input for cart_id -->
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
                                                    <strong>{{ $defaultAddress->location_name }}</strong>
                                                    @if($defaultAddress->is_default)
                                                        <span class="badge bg-danger ms-2">Default</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 small">{{ $defaultAddress->address_line1 }}</p>
                                                @if($defaultAddress->address_line2)
                                                    <p class="mb-1 small">{{ $defaultAddress->address_line2 }}</p>
                                                @endif
                                                <p class="mb-1 small">{{ $defaultAddress->city }}, {{ $defaultAddress->state }} {{ $defaultAddress->postal_code }}</p>
                                                @if($defaultAddress->phone_number)
                                                    <p class="mb-0 small">{{ $defaultAddress->phone_number }}</p>
                                                @endif
                                                <input type="hidden" name="location_id" value="{{ $defaultAddress->location_id }}">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addressSelectionModal">
                                                Change
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="bi bi-geo-alt-fill text-muted fs-4"></i>
                                            <p class="mb-2 small">No shipping address found</p>
                                            <button type="button" class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addressSelectionModal">
                                                Add Address
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Add this hidden input for cart_id -->
                            <input type="hidden" name="cart_id" value="{{ $cart->cart_id }}">
                            
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Payment Method</h6>
                                <div class="payment-methods">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_toyyibpay" value="toyyibpay" checked>
                                        <label class="form-check-label d-flex align-items-center" for="payment_toyyibpay">
                                            <i class="bi bi-credit-card payment-icon me-2"></i>
                                            Online Payment (Credit/Debit Card)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cash_on_delivery">
                                        <label class="form-check-label d-flex align-items-center" for="payment_cod">
                                            <i class="bi bi-cash-coin payment-icon me-2"></i>
                                            Cash on Delivery
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Keep the existing Place Order button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary-custom py-2 pulse-animation" id="place-order-btn">
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

<!-- Address Selection Modal -->
<div class="modal fade" id="addressSelectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach(auth()->user()->locations as $location)
                <div class="address-option mb-3 p-3 border rounded">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selected_address" 
                               id="address-{{ $location->location_id }}" 
                               value="{{ $location->location_id }}">
                        <label class="form-check-label" for="address-{{ $location->location_id }}">
                            <strong>{{ $location->location_name }}</strong>
                            <p class="mb-1 small">{{ $location->address_line1 }}</p>
                            @if($location->address_line2)
                                <p class="mb-1 small">{{ $location->address_line2 }}</p>
                            @endif
                            <p class="mb-1 small">{{ $location->city }}, {{ $location->state }} {{ $location->postal_code }}</p>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectAddressBtn">Select</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 library is already included -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Address selection handling for modal
    initAddressSelection();
    
    // Form submission handling
    initCheckoutForm();
    
    // Handle return from payment gateway
    handlePaymentReturn();
});

// Function to handle address selection
function initAddressSelection() {
    console.log('initAddressSelection function called');
    const selectAddressBtn = document.getElementById('selectAddressBtn');
    console.log('selectAddressBtn element:', selectAddressBtn);
    
    if (selectAddressBtn) {
        selectAddressBtn.addEventListener('click', function() {
            console.log('selectAddressBtn clicked');
            // For existing addresses
            const selectedAddressRadio = document.querySelector('input[name="selected_address"]:checked');
            console.log('Selected address radio:', selectedAddressRadio);
            
            if (selectedAddressRadio) {
                const locationId = selectedAddressRadio.value;
                console.log('Selected location ID:', locationId);
                const addressLabel = document.querySelector(`label[for="address-${locationId}"]`).innerHTML;
                console.log('Address label HTML:', addressLabel);
                
                // Update the hidden input in the main form
                const locationIdInput = document.querySelector('input[name="location_id"]');
                console.log('Location ID input element:', locationIdInput);
                
                if (locationIdInput) {
                    locationIdInput.value = locationId;
                    console.log('Updated location_id input value to:', locationId);
                } else {
                    console.error('Could not find location_id input element');
                }
                
                // Update the displayed address
                const addressSelectionArea = document.getElementById('address-selection-area');
                console.log('Address selection area element:', addressSelectionArea);
                
                if (addressSelectionArea) {
                    // Extract address details from the label
                    const addressName = addressLabel.match(/<strong>(.*?)<\/strong>/)[1];
                    console.log('Extracted address name:', addressName);
                    const addressLines = addressLabel.split('<p class="mb-1 small">');
                    console.log('Address lines array:', addressLines);
                    
                    // Create HTML for the selected address
                    let addressHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                <strong>${addressName}</strong>
                            </div>`;
                    
                    // Add address lines (skip the first element which is before the first <p>)
                    for (let i = 1; i < addressLines.length; i++) {
                        const line = addressLines[i].split('</p>')[0];
                        addressHTML += `<p class="mb-1 small">${line}</p>`;
                    }
                    
                    addressHTML += `
                            <input type="hidden" name="location_id" value="${locationId}">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addressSelectionModal">
                            Change
                        </button>
                    </div>`;
                    
                    console.log('New address HTML to be inserted:', addressHTML);
                    addressSelectionArea.innerHTML = addressHTML;
                    console.log('Address selection area updated with new HTML');
                } else {
                    console.error('Could not find address-selection-area element');
                }
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addressSelectionModal'));
                console.log('Modal instance:', modal);
                modal.hide();
                console.log('Modal hidden');
            } else {
                console.warn('No address selected');
                alert('Please select an address');
            }
        });
        console.log('Event listener added to selectAddressBtn');
    } else {
        console.error('selectAddressBtn element not found');
    }
}

// Also add console logs to the second implementation at the end of the file
// Replace or modify the existing code around line 659
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired for second implementation');
    const selectBtn = document.getElementById('selectAddressBtn');
    console.log('Second implementation - selectBtn element:', selectBtn);
    
    if (selectBtn) {
        selectBtn.addEventListener('click', function() {
            console.log('Second implementation - selectBtn clicked');
            const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
            console.log('Second implementation - selected address:', selectedAddress);
            
            if (selectedAddress) {
                const locationId = selectedAddress.value;
                console.log('Second implementation - location ID:', locationId);
                const locationIdInput = document.querySelector('input[name="location_id"]');
                console.log('Second implementation - location ID input:', locationIdInput);
                
                if (locationIdInput) {
                    locationIdInput.value = locationId;
                    console.log('Second implementation - updated location_id to:', locationId);
                } else {
                    console.error('Second implementation - could not find location_id input');
                }
                
                $('#addressSelectionModal').modal('hide');
                console.log('Second implementation - modal hidden');
            } else {
                console.warn('Second implementation - no address selected');
                alert('Please select an address');
            }
        });
        console.log('Second implementation - event listener added');
    } else {
        console.error('Second implementation - selectBtn not found');
    }
});

// Add console logs to validateShippingAddress function
function validateShippingAddress() {
    console.log('validateShippingAddress function called');
    const locationIdInput = document.querySelector('input[name="selected_address"]');
    console.log('Location ID input in validation:', locationIdInput);
    
    if (locationIdInput) {
        console.log('Location ID value:', locationIdInput.value);
        if (!locationIdInput.value) {
            console.error('No location ID selected');
            alert('Please select a shipping address');
            return false;
        }
        console.log('Shipping address validation passed');
        return true;
    } else {
        console.error('Location ID input not found during validation');
        alert('Please select a shipping address');
        return false;
    }
}

// Add console logs to initCheckoutForm function
function initCheckoutForm() {
    console.log('initCheckoutForm function called');
    const checkoutForm = document.getElementById('checkout-form');
    console.log('Checkout form element:', checkoutForm);
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            console.log('Checkout form submit event triggered');
            // Prevent default form submission
            e.preventDefault();

            // Validate shipping address
            console.log('Calling validateShippingAddress()');
            if (!validateShippingAddress()) {
                console.log('Shipping address validation failed');
                return false;
            }
            
            // Get selected payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            console.log('Selected payment method:', paymentMethod);
            
            // Prepare the form for submission (show loading indicators, etc.)
            prepareFormForSubmission(paymentMethod);
            
            // For online payment, you might want to use AJAX to submit the form
            if (paymentMethod === 'toyyibpay') {
                // Option 1: Submit form normally (will refresh the page but your prepareFormForSubmission handles this)
                console.log('Form validation passed, submitting form');
                this.submit(); // Use 'this' instead of checkoutForm to avoid the recursive event loop
                
                // Option 2: Use AJAX to submit the form (won't refresh the page)
                // const formData = new FormData(this);
                // fetch(this.action, {
                //     method: 'POST',
                //     body: formData,
                //     headers: {
                //         'X-Requested-With': 'XMLHttpRequest'
                //     }
                // })
                // .then(response => response.json())
                // .then(data => {
                //     // Handle the response (redirect to payment gateway, etc.)
                //     if (data.redirect_url) {
                //         window.location.href = data.redirect_url;
                //     }
                // })
                // .catch(error => {
                //     console.error('Error:', error);
                //     // Handle error
                // });
            } else {
                // For cash on delivery, submit normally
                console.log('Form validation passed, submitting form');
                this.submit(); // Use 'this' instead of checkoutForm
            }
        });
        console.log('Submit event listener added to checkout form');
    } else {
        console.error('Checkout form element not found');
    }
}

// // Function to validate shipping address
// function validateShippingAddress() {
//     const shippingAddressInput = document.querySelector('input[name="shipping_address"]');
//     if (!shippingAddressInput || !shippingAddressInput.value) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Address Required',
//             text: 'Please select a shipping address before placing your order.',
//             confirmButtonColor: '#3085d6'
//         });
//         return false;
//     }
//     return true;
// }

// Function to prepare form for submission
function prepareFormForSubmission(paymentMethod) {
    // Update button state
    const placeOrderBtn = document.getElementById('place-order-btn');
    placeOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    placeOrderBtn.disabled = true;
    
    // Add a security token to prevent CSRF
    const securityToken = document.createElement('input');
    securityToken.type = 'hidden';
    securityToken.name = 'security_token';
    securityToken.value = Date.now().toString() + Math.random().toString(36).substring(2, 15);
    document.getElementById('checkout-form').appendChild(securityToken);
    
    // Show processing overlay for better UX
    if (paymentMethod === 'toyyibpay') {
        showProcessingOverlay();
        setupRedirectTimeout();
    }
}

// Function to show processing overlay
function showProcessingOverlay() {
    const processingOverlay = document.createElement('div');
    processingOverlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center';
    processingOverlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
    processingOverlay.style.zIndex = '9999';
    processingOverlay.innerHTML = `
        <div class="bg-white p-4 rounded shadow-lg text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Processing Your Order</h5>
            <p class="mb-0 text-muted small">Please do not close this window...</p>
        </div>
    `;
    document.body.appendChild(processingOverlay);
}

// Function to setup redirect timeout
function setupRedirectTimeout() {
    // Set a timeout to handle potential redirect issues
    const redirectTimeout = setTimeout(() => {
        Swal.fire({
            icon: 'error',
            title: 'Connection Issue',
            text: 'We\'re having trouble connecting to the payment gateway. Please try again.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            const placeOrderBtn = document.getElementById('place-order-btn');
            placeOrderBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Place Order';
            placeOrderBtn.disabled = false;
            
            // Remove the processing overlay
            const processingOverlay = document.querySelector('.position-fixed.top-0.start-0.w-100.h-100');
            if (processingOverlay) {
                processingOverlay.remove();
            }
        });
    }, 30000); // 30 seconds timeout
    
    // Store the timeout ID in sessionStorage to clear it if redirect is successful
    sessionStorage.setItem('redirectTimeoutId', redirectTimeout);
    
    // Add event listener for beforeunload to detect successful redirect
    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('redirecting', 'true');
    });
}

// Function to handle return from payment gateway
function handlePaymentReturn() {
    // Check if returning from payment gateway
    if (sessionStorage.getItem('redirecting') === 'true') {
        // Clear the timeout to prevent error message
        const timeoutId = sessionStorage.getItem('redirectTimeoutId');
        if (timeoutId) {
            clearTimeout(parseInt(timeoutId));
        }
        
        // Clear session storage
        sessionStorage.removeItem('redirecting');
        sessionStorage.removeItem('redirectTimeoutId');
        
        // Show success message if needed
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('payment_status')) {
            const status = urlParams.get('payment_status');
            if (status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful',
                    text: 'Your order has been placed successfully!',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    }
}
</script>
{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const selectBtn = document.getElementById('selectAddressBtn');
    
    if (selectBtn) {
        selectBtn.addEventListener('click', function() {
            const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
            
            if (selectedAddress) {
                const locationId = selectedAddress.value;
                document.querySelector('input[name="location_id"]').value = locationId;
                $('#addressSelectionModal').modal('hide');
                
                // Update the displayed address (optional)
                // You would need to fetch the address details via AJAX or reload the page
            } else {
                alert('Please select an address');
            }
        });
    }
});
</script> --}}
@endsection