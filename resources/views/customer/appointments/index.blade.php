@extends('layout')

@section('title', 'My Appointments - Tradicare')

@section('content')
<!-- Hero Section -->
<div class="services-hero py-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">My Appointments</h1>
                <p class="lead">View and manage your scheduled wellness sessions</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Book New Appointment
        </a>
        
        <!-- Filters -->
        <div class="d-flex gap-2">
            <!-- Status Filter -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item {{ request('status') == '' ? 'active' : '' }}" href="{{ route('customer.appointments.index') }}">All Appointments</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'scheduled' ? 'active' : '' }}" href="{{ route('customer.appointments.index', ['status' => 'scheduled']) }}">Scheduled</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('customer.appointments.index', ['status' => 'completed']) }}">Completed</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('customer.appointments.index', ['status' => 'cancelled']) }}">Cancelled</a></li>
                </ul>
            </div>
        
            <!-- Date Filter -->
            <form action="{{ route('customer.appointments.index') }}" method="GET" class="d-flex align-items-center">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group">
                    <input type="date" class="form-control form-control-sm" id="date_filter" name="date" value="{{ request('date') }}" placeholder="Filter by date">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-calendar"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments List -->
    @if($appointments->isEmpty())
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No Appointments Found</h5>
                <p class="text-muted">You haven't booked any appointments yet.</p>
                <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary mt-2">
                    Book Your First Appointment
                </a>
            </div>
        </div>
    @else
        <!-- Appointments Grid -->
        <div class="row g-4">
            @foreach($appointments as $appointment)
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <!-- Card Header -->
                    <div class="card-header bg-light border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill px-3 py-2 
                                @if($appointment->status == 'scheduled') bg-primary
                                @elseif($appointment->status == 'completed') bg-success
                                @elseif($appointment->status == 'cancelled') bg-danger
                                @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <span class="text-muted small">Appointment #{{ $appointment->appointment_id }}</span>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <!-- Service Info -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon-sm bg-primary-light rounded-circle p-3 me-3">
                                <i class="bi {{ $appointment->service->icon ?? 'bi-gem' }} text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $appointment->service->service_name }}</h5>
                                <p class="mb-0 text-muted">{{ $appointment->service->duration_minutes }} minutes</p>
                            </div>
                        </div>
                        
                        <!-- Appointment Details -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-date text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Date</small>
                                        <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Time</small>
                                        <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Info -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-currency-dollar text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Amount</small>
                                        <p class="mb-0 fw-medium">RM{{ number_format($appointment->service->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-credit-card text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Payment</small>
                                        <span class="badge 
                                            @if($appointment->payment && $appointment->payment->status == 'completed') bg-success
                                            @elseif($appointment->payment && $appointment->payment->status == 'pending') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            @if($appointment->payment)
                                                {{ ucfirst($appointment->payment->status) }}
                                            @else
                                                Pending
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        @if($appointment->mobile_number)
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-phone text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Contact</small>
                                <p class="mb-0 fw-medium">{{ $appointment->mobile_number }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="card-footer bg-light border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Booked on {{ \Carbon\Carbon::parse($appointment->created_at)->format('M j, Y') }}
                            </small>
                            
                            @if($appointment->status == 'scheduled')
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewAppointment({{ $appointment->appointment_id }})">
                                    <i class="bi bi-eye me-1"></i>View
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelAppointment({{ $appointment->appointment_id }})">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </button>
                            </div>
                            @else
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewAppointment({{ $appointment->appointment_id }})">
                                <i class="bi bi-eye me-1"></i>View Details
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $appointments->links() }}
        </div>
        @endif
    @endif
</div>

<style>
/* Custom Styles */
.service-icon-sm {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
    transform: translateY(20px);
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
    margin-left: 0.25rem;
}

.btn-group .btn:first-child {
    margin-left: 0;
}
</style>

<script>
// Appointment Management Functions
function viewAppointment(appointmentId) {
    // Redirect to appointment details or show modal
    window.location.href = `/customer/appointments/${appointmentId}`;
}

function cancelAppointment(appointmentId) {
    if (confirm('Are you sure you want to cancel this appointment?')) {
        fetch(`/customer/appointments/${appointmentId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Appointment cancelled successfully.');
                location.reload();
            } else {
                alert(data.message || 'Failed to cancel appointment.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

// Auto-submit date filter
document.getElementById('date_filter').addEventListener('change', function() {
    this.closest('form').submit();
});
</script>
@endsection

@section('css')
<style>
    .fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .service-icon-sm {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }

    /* Payment Popup Styles */
    .payment-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .popup-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(2px);
    }

    .popup-content {
        position: relative;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: popupSlideIn 0.3s ease-out;
    }

    @keyframes popupSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .popup-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px 12px 0 0;
    }

    .popup-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: #6c757d;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .close-btn:hover {
        background-color: #f8f9fa;
        color: #333;
    }

    .popup-body {
        padding: 24px;
    }

    .popup-footer {
        padding: 16px 24px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background-color: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    /* Payment Card Styles */
    .payment-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
        border-radius: 8px;
    }

    .payment-card:hover {
        border-color: #493628;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .payment-option .payment-card.selected {
        border-color: #493628 !important;
        background-color: #f8f9ff !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .summary-item label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .booking-summary {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #493628;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .popup-content {
            width: 95%;
            margin: 10px;
        }
        
        .popup-body {
            padding: 16px;
        }
        
        .popup-header, .popup-footer {
            padding: 16px;
        }
    }
</style>
@endsection

@section('js')
<script>
// Function to open payment popup
function openPaymentPopup(appointmentId, serviceName, price, datetime, duration) {
    // Set appointment ID
    document.getElementById('appointment-id').value = appointmentId;
    
    // Update popup content
    document.getElementById('modal-service-name').textContent = serviceName;
    
    // Parse datetime to separate date and time
    const datetimeParts = datetime.split(' ');
    const datePart = datetimeParts.slice(0, 3).join(' '); // "January 15, 2024"
    const timePart = datetimeParts.slice(3).join(' '); // "2:30 PM"
    
    document.getElementById('modal-date').textContent = datePart;
    document.getElementById('modal-time').textContent = timePart;
    document.getElementById('modal-duration').textContent = duration;
    document.getElementById('modal-total-price').textContent = price;
    
    // Show the payment popup
    const paymentPopup = document.getElementById('paymentPopup');
    if (paymentPopup) {
        paymentPopup.style.display = 'flex';
        
        // Initialize payment method selection
        initializePaymentMethodSelection();
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
}

// Function to close payment popup
function closePaymentPopup() {
    const paymentPopup = document.getElementById('paymentPopup');
    if (paymentPopup) {
        paymentPopup.style.display = 'none';
        
        // Restore body scroll
        document.body.style.overflow = 'auto';
        
        // Reset payment method selection
        resetPaymentSelection();
    }
}

// Initialize payment method selection
function initializePaymentMethodSelection() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const confirmBtn = document.getElementById('confirm-payment-btn');
    
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove previous selection
            paymentOptions.forEach(opt => {
                opt.querySelector('.payment-card').classList.remove('selected');
                opt.querySelector('input[type="radio"]').checked = false;
            });
            
            // Add selection to clicked option
            this.querySelector('.payment-card').classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            
            // Enable confirm button
            confirmBtn.disabled = false;
        });
    });
}

// Reset payment selection
function resetPaymentSelection() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const confirmBtn = document.getElementById('confirm-payment-btn');
    
    paymentOptions.forEach(opt => {
        opt.querySelector('.payment-card').classList.remove('selected');
        opt.querySelector('input[type="radio"]').checked = false;
    });
    
    confirmBtn.disabled = true;
}

// Function to process payment from popup
function processPaymentFromPopup() {
    const appointmentId = document.getElementById('appointment-id').value;
    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
    
    if (!selectedPaymentMethod) {
        alert('Please select a payment method.');
        return;
    }
    
    const paymentMethod = selectedPaymentMethod.value;
    
    // Show loading state
    const submitBtn = document.getElementById('confirm-payment-btn');
    const originalText = submitBtn.innerHTML;
    const spinner = submitBtn.querySelector('.spinner-border');
    
    spinner.classList.remove('d-none');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
    submitBtn.disabled = true;
    
    // Make AJAX request to process payment
    fetch(`{{ route('customer.appointments.payment.process', ':appointmentId') }}`.replace(':appointmentId', appointmentId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            payment_method: paymentMethod
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect_url) {
                // For ToyyibPay, redirect to payment gateway
                window.location.href = data.redirect_url;
            } else {
                // For cash payment, close popup and reload page
                closePaymentPopup();
                window.location.reload();
            }
        } else {
            alert(data.message || 'Payment processing failed. Please try again.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Legacy function for backward compatibility
function openPaymentModal(appointmentId, serviceName, price, datetime, duration) {
    openPaymentPopup(appointmentId, serviceName, 'RM' + parseFloat(price).toFixed(2), datetime, duration + ' minutes');
}

// Legacy function for backward compatibility
function processPayment() {
    const appointmentId = document.getElementById('appointment-id').value;
    
    if (!appointmentId) {
        alert('Please select an appointment first.');
        return;
    }
    
    // This function is kept for compatibility but should use openPaymentPopup instead
    console.warn('processPayment() is deprecated. Use openPaymentPopup() instead.');
}
</script>
@endsection