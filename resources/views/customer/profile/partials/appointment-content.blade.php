<div class="tab-content" id="appointmentTabsContent">
    <!-- To Pay Appointments Tab -->
    <div class="tab-pane fade show active" id="to-pay-appointments" role="tabpanel" aria-labelledby="to-pay-appointments-tab">
        @php
            $toPayAppointments = isset($appointments) ? $appointments->filter(function($appointment) {
                return ($appointment->payment && $appointment->payment->status == 'pending') || !$appointment->payment;
            }) : collect();
        @endphp
        
        @if($toPayAppointments->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No appointments to pay</h5>
                <p class="text-muted">You don't have any pending payments for appointments.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($toPayAppointments as $appointment)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <!-- Card Header -->
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill px-3 py-2 bg-warning">
                                    Payment Pending
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
                                    <h6 class="mb-1 fw-bold">{{ $appointment->service->service_name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $appointment->service->duration_minutes }} minutes</p>
                                </div>
                            </div>
                            
                            <!-- Appointment Details -->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-date text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Date</small>
                                            <p class="mb-0 fw-medium small">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Time</small>
                                            <p class="mb-0 fw-medium small">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('g:i A') }}</p>
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
                                            <p class="mb-0 fw-medium small">RM{{ number_format($appointment->service->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-credit-card text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Payment</small>
                                            <span class="badge bg-warning">
                                                @if($appointment->payment)
                                                    @if($appointment->payment->status == 'confirmed')
                                                        Confirmed
                                                    @elseif($appointment->payment->status == 'completed')
                                                        Completed
                                                    @else
                                                        {{ ucfirst($appointment->payment->status) }}
                                                    @endif
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
                                    <p class="mb-0 fw-medium small">{{ $appointment->mobile_number }}</p>
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
                                
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('customer.payment.process', ['type' => 'appointment', 'id' => $appointment->appointment_id]) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="toyyibpay">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-credit-card me-1"></i>Pay Now
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="openCancelAppointmentModal({{ $appointment->appointment_id }})">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Scheduled Appointments Tab -->
    <div class="tab-pane fade" id="scheduled" role="tabpanel" aria-labelledby="scheduled-tab">
        @php
            $scheduledAppointments = isset($appointments) ? $appointments->filter(function($appointment) {
                return $appointment->status == 'scheduled' && $appointment->payment && $appointment->payment->status == 'completed';
            }) : collect();
        @endphp
        
        @if($scheduledAppointments->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-check text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No scheduled appointments</h5>
                <p class="text-muted">You don't have any scheduled appointments.</p>
                <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle me-2"></i>Book Your First Appointment
                </a>
            </div>
        @else
            <div class="row g-3">
                @foreach($scheduledAppointments as $appointment)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <!-- Card Header -->
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill px-3 py-2 bg-primary">
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
                                    <h6 class="mb-1 fw-bold">{{ $appointment->service->service_name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $appointment->service->duration_minutes }} minutes</p>
                                </div>
                            </div>
                            
                            <!-- Appointment Details -->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-date text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Date</small>
                                            <p class="mb-0 fw-medium small">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Time</small>
                                            <p class="mb-0 fw-medium small">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('g:i A') }}</p>
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
                                            <p class="mb-0 fw-medium small">RM{{ number_format($appointment->service->price, 2) }}</p>
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
                                                    @if($appointment->payment->status == 'completed' && $appointment->payment->payment_method == 'cash')
                                                        To pay
                                                    @elseif($appointment->payment->status == 'completed')
                                                        Completed
                                                    @else
                                                        {{ ucfirst($appointment->payment->status) }}
                                                    @endif
                                                @else
                                                    Pending
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Info -->
                            @if($appointment->tel_number)
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-phone text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Contact</small>
                                    <p class="mb-0 fw-medium small">{{ $appointment->tel_number }}</p>
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
                                
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="openCancelAppointmentModal({{ $appointment->appointment_id }})">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Cancelled Appointments Tab -->
    <div class="tab-pane fade" id="cancelled-appointments" role="tabpanel" aria-labelledby="cancelled-appointments-tab">
        @php
            $cancelledAppointments = isset($appointments) ? $appointments->filter(function($appointment) {
                return $appointment->status == 'cancelled' && $appointment->payment && $appointment->payment->status == 'completed';
            }) : collect();
        @endphp
        
        @if($cancelledAppointments->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-x-circle text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No cancelled appointments</h5>
                <p class="text-muted">You don't have any cancelled appointments.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($cancelledAppointments as $appointment)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <!-- Card Header -->
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill px-3 py-2 bg-danger">
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
                                    <h6 class="mb-1 fw-bold">{{ $appointment->service->service_name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $appointment->service->duration_minutes }} minutes</p>
                                </div>
                            </div>
                            
                            <!-- Appointment Details -->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-date text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Date</small>
                                            <p class="mb-0 fw-medium small">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Time</small>
                                            <p class="mb-0 fw-medium small">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('g:i A') }}</p>
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
                                            <p class="mb-0 fw-medium small">RM{{ number_format($appointment->service->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-credit-card text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Payment</small>
                                            <span class="badge bg-secondary">
                                                @if($appointment->payment)
                                                    {{ ucfirst($appointment->payment->status) }}
                                                @else
                                                    Cancelled
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
                                    <p class="mb-0 fw-medium small">{{ $appointment->mobile_number }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer bg-light border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Cancelled on {{ \Carbon\Carbon::parse($appointment->updated_at)->format('M j, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.service-icon-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1) !important;
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

.nav-tabs .nav-link {
    color: #6c757d;
    border: 1px solid transparent;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
    isolation: isolate;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}
</style>

<!-- Add this at the end of the file -->
<script>
function openCancelAppointmentModal(appointmentId) {
    // Set the appointment ID in the modal button
    document.getElementById('confirmCancelAppointment').setAttribute('data-appointment-id', appointmentId);
    
    // Open the modal
    const modal = new bootstrap.Modal(document.getElementById('cancelAppointmentModal'));
    modal.show();
}
</script>