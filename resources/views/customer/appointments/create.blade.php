@extends('layout')

@section('title', 'Book Appointment - Tradicare')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="h3 fw-bold text-primary-custom">Book Your Appointment</h2>
                        <p class="text-muted">Complete the form below to schedule your wellness session</p>
                    </div>
                    
                    <form action="{{ route('customer.appointments.store') }}" method="POST" id="appointment-form">
                        @csrf
                        
                        @if(isset($selectedService))
                            <input type="hidden" name="service_id" value="{{ $selectedService->service_id }}">
                            <div class="selected-service mb-4">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="flex-shrink-0">
                                        <div class="service-icon-sm">
                                            <i class="bi {{ $selectedService->icon ?? 'bi-gem' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">{{ $selectedService->service_name }}</h5>
                                        <div class="d-flex text-muted small">
                                            <span class="me-3"><i class="bi bi-clock me-1"></i> {{ $selectedService->duration_minutes }} mins</span>
                                            <span><i class="bi bi-tag me-1"></i> RM{{ number_format($selectedService->price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <label for="service_id" class="form-label">Select Service</label>
                                <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                    <option value="">Choose a service...</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->service_id }}" 
                                                data-duration="{{ $service->duration_minutes }}"
                                                data-price="{{ $service->price }}">
                                            {{ $service->service_name }} - {{ $service->duration_minutes }} mins (RM{{ number_format($service->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="appointment_date" class="form-label">Preferred Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                           value="{{ $selectedDate ?? date('Y-m-d', strtotime('+1 day')) }}"
                                           required min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Available Time Slots -->
                        <div class="mb-4">
                            <label class="form-label">Available Time Slots</label>
                            <div class="time-slots-container">
                                @if(count($availableTimeSlots ?? []) > 0)
                                    <div class="time-slots-grid">
                                        @foreach($availableTimeSlots as $slot)
                                            <div class="time-slot-item">
                                                <input type="radio" class="btn-check" name="appointment_time" 
                                                       id="time-{{ $loop->index }}" value="{{ $slot['value'] }}" 
                                                       autocomplete="off" required>
                                                <label class="btn btn-outline-secondary w-100" for="time-{{ $loop->index }}">
                                                    {{ $slot['time'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> No available slots for the selected date. Please try another date.
                                    </div>
                                @endif
                            </div>
                            @error('appointment_time')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Special Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                    placeholder="Any special requests or health concerns we should know about?"></textarea>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms_agreed" name="terms_agreed" required>
                                <label class="form-check-label" for="terms_agreed">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                                </label>
                                @error('terms_agreed')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-calendar-check me-2"></i> Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Appointment Policy</h6>
                <p>Please arrive 15 minutes before your scheduled appointment time to complete any necessary paperwork and prepare for your treatment.</p>
                
                <h6>Cancellation Policy</h6>
                <p>We understand that schedules change. We ask that you notify us at least 24 hours in advance if you need to cancel or reschedule your appointment to avoid a cancellation fee.</p>
                
                <h6>Late Arrival Policy</h6>
                <p>If you arrive late for your scheduled appointment, your treatment time may be reduced to accommodate other scheduled appointments. Full treatment prices will apply.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-custom" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .service-icon-sm {
        width: 50px;
        height: 50px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-icon-sm i {
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(214, 192, 179, 0.25);
    }
    
    .input-group-text {
        background-color: var(--primary-light);
        color: var(--primary);
        border-color: var(--primary-light);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min time based on current time if date is today
        const appointmentDate = document.getElementById('appointment_date');
        const appointmentTime = document.getElementById('appointment_time');
        
        appointmentDate.addEventListener('change', function() {
            const today = new Date().toISOString().split('T')[0];
            if (appointmentDate.value === today) {
                const now = new Date();
                let hours = now.getHours() + 1; // Add 1 hour buffer
                if (hours < 9) hours = 9; // Opening time
                if (hours >= 20) hours = 19; // Last appointment time
                
                const minTime = `${hours.toString().padStart(2, '0')}:00`;
                appointmentTime.min = minTime;
            } else {
                appointmentTime.min = '09:00'; // Opening time
            }
            appointmentTime.max = '20:00'; // Closing time
        });
        
        // Trigger change event to set initial min time
        appointmentDate.dispatchEvent(new Event('change'));
    });
</script>
@endsection