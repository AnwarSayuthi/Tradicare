<!-- Appointments List -->
<div class="row g-3">
    @foreach($appointments as $appointment)
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 h-100">
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
                    
                    @if($appointment->status == 'scheduled')
                    <div class="btn-group" role="group">
                        {{-- <a href="{{ route('customer.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a> --}}
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelAppointment({{ $appointment->appointment_id }})">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                    </div>
                    @else
                    {{-- <a href="{{ route('customer.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>View Details --}}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($appointments->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $appointments->links() }}
</div>
@endif

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
</style>