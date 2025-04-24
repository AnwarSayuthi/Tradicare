@extends('layout')

@section('title', 'Complete Payment - Tradicare')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="h3 fw-bold text-primary-custom">Complete Your Payment</h2>
                        <p class="text-muted">Secure your appointment with a payment</p>
                    </div>
                    
                    <div class="appointment-summary mb-4">
                        <div class="card bg-light border-0 rounded-3">
                            <div class="card-body p-4">
                                <h5 class="mb-3 fw-bold">Appointment Summary</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-container me-2 bg-primary-light rounded-circle p-2">
                                                <i class="bi bi-gem text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Service</small>
                                                <p class="mb-0 fw-medium">{{ $appointment->service->service_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-container me-2 bg-primary-light rounded-circle p-2">
                                                <i class="bi bi-calendar-date text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Date & Time</small>
                                                <p class="mb-0 fw-medium">{{ $appointment->appointment_date->format('F j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-container me-2 bg-primary-light rounded-circle p-2">
                                                <i class="bi bi-clock text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Duration</small>
                                                <p class="mb-0 fw-medium">{{ $appointment->service->duration_minutes }} minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-container me-2 bg-primary-light rounded-circle p-2">
                                                <i class="bi bi-tag text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Price</small>
                                                <p class="mb-0 fw-medium">RM{{ number_format($appointment->service->price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('customer.appointment.process-payment', $appointment->appointment_id) }}" method="POST" id="payment-form">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="mb-3">Payment Method</h5>
                            
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
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg">Complete Payment</button>
                            <a href="{{ route('customer.appointments') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-method-option {
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .payment-method-option:hover {
        background-color: #f8f9fa;
    }
    
    .payment-method-option .form-check-input:checked ~ .form-check-label {
        color: var(--primary);
    }
    
    .payment-method-option .form-check-input:checked ~ .form-check-label .payment-icon {
        color: var(--primary);
    }
    
    .payment-icon {
        width: 40px;
        height: 40px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .icon-container {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-primary-light {
        background-color: rgba(73, 54, 40, 0.1);
    }
</style>
@endsection