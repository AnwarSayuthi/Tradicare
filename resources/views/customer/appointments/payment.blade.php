@extends('layout')

@section('title', 'Complete Payment - Tradicare')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Booking Details Column -->
                        <div class="col-md-4 p-4 p-md-5 bg-light">
                            <h5 class="fw-bold mb-4">Booking Details</h5>
                            
                            <!-- Service Details -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Service Details</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>{{ $appointment->service->service_name }}</div>
                                    <div class="fw-bold">RM{{ number_format($appointment->service->price, 2) }}</div>
                                </div>
                                <div class="text-muted small">{{ $appointment->service->duration_minutes }} minutes</div>
                            </div>
                            
                            <!-- Date & Time -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Date & Time</h6>
                                <div class="date-time-info">
                                    <p class="mb-1">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</p>
                                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, d M Y') }}</p>
                                </div>
                            </div>
                            
                            <!-- Booked for -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Booked for</h6>
                                <div class="booked-for-details">
                                    <span class="badge bg-primary">{{ auth()->user()->name }}</span>
                                </div>
                            </div>
                            
                            <!-- Total Amount -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Total Amount</h6>
                                <div class="total-amount">
                                    <h4 class="fw-bold">RM{{ number_format($appointment->service->price, 2) }}</h4>
                                </div>
                            </div>
                            
                            <!-- Help Section -->
                            <div class="help-section">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-headset fs-3 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">We can help you</h6>
                                        <p class="mb-0 small">Call +60 123 456 789 for chat with our customer support team</p>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-outline-primary btn-sm mt-3 w-100">Chat with us</a>
                            </div>
                        </div>
                        
                        <!-- Payment Form Column -->
                        <div class="col-md-8 p-4 p-md-5">
                            <div class="d-flex align-items-center mb-4">
                                <a href="{{ route('customer.appointments.create') }}" class="text-decoration-none text-dark me-3">
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                                <h5 class="mb-0 fw-bold">Payment Details</h5>
                            </div>
                            
                            <form action="{{ route('customer.appointments.process-payment', $appointment->appointment_id) }}" method="POST" id="payment-form">
                                @csrf
                                
                                <div class="mb-4">
                                    <h6 class="form-section-title mb-3">Payment Method</h6>
                                    
                                    <div class="payment-methods">
                                        <div class="form-check payment-method-option mb-3">
                                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                            <label class="form-check-label d-flex align-items-center" for="credit_card">
                                                <div class="payment-icon me-3">
                                                    <i class="bi bi-credit-card"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-medium">Credit/Debit Card</span>
                                                    <small class="text-muted">Pay securely with your card</small>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check payment-method-option mb-3">
                                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                            <label class="form-check-label d-flex align-items-center" for="paypal">
                                                <div class="payment-icon me-3">
                                                    <i class="bi bi-paypal"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-medium">PayPal</span>
                                                    <small class="text-muted">Pay via PayPal</small>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check payment-method-option">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                                            <label class="form-check-label d-flex align-items-center" for="cash">
                                                <div class="payment-icon me-3">
                                                    <i class="bi bi-cash"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-medium">Cash on Arrival</span>
                                                    <small class="text-muted">Pay when you arrive for your appointment</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="credit-card-details" class="mb-4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="card_number" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="expiry_date" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="expiry_date" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" placeholder="123">
                                        </div>
                                        <div class="col-12">
                                            <label for="card_name" class="form-label">Name on Card</label>
                                            <input type="text" class="form-control" id="card_name" placeholder="John Doe">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Right Side - Booking Details -->
                        <div class="col-lg-5 p-4 p-md-5 bg-light">
                            <h5 class="fw-bold mb-4">Booking Details</h5>
                            
                            <div class="booking-details">
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Service Details</h6>
                                    <div class="p-3 bg-white rounded-3 shadow-sm">
                                        <h6 class="fw-bold mb-2">{{ $appointment->service->service_name }}</h6>
                                        <p class="mb-0 small">{{ $appointment->service->duration_minutes }} minutes</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Date & Time</h6>
                                    <div class="p-3 bg-white rounded-3 shadow-sm">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar-date me-2 text-primary"></i>
                                            <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, d M Y') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock me-2 text-primary"></i>
                                            <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Booked for</h6>
                                    <div class="p-3 bg-white rounded-3 shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-light rounded-circle p-2 me-3 text-center">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ auth()->user()->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="total-amount mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0">Total Amount</h6>
                                        <div class="total-price fw-bold">RM{{ number_format($appointment->service->price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.booking-details-card {
    background-color: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
}

.section-title {
    font-size: 0.9rem;
    font-weight: 500;
}

.package-name {
    font-weight: 500;
}

.date-time-info {
    font-weight: 500;
}

.support-icon {
    width: 50px;
    height: 50px;
    background-color: #f0f4ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0d6efd;
}

.payment-method-option {
    transition: all 0.2s;
}

.payment-method-option:hover {
    border-color: #0d6efd !important;
}

.form-check-input:checked + .form-check-label .payment-icon {
    background-color: #e6f0ff !important;
}

.form-check-input:checked + .form-check-label {
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardForm = document.getElementById('credit-card-form');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardForm.style.display = 'block';
            } else {
                creditCardForm.style.display = 'none';
            }
        });
    });
});
</script>
@endsection