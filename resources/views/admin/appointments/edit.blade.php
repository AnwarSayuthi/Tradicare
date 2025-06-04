@extends('admin_layout')

@section('title', 'Edit Appointment - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Edit Appointment #{{ $appointment->appointment_id }}</h4>
                        <p class="text-muted mb-0">Update appointment details</p>
                    </div>
                    <a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Details
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.appointments.update', $appointment->appointment_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Customer</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('user_id', $appointment->user_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="service_id" class="form-label">Service</label>
                                <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                    <option value="">Select Service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->service_id }}" {{ old('service_id', $appointment->service_id) == $service->service_id ? 'selected' : '' }}>
                                            {{ $service->service_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="available_time_id" class="form-label">Time Slot</label>
                                <select class="form-select @error('available_time_id') is-invalid @enderror" id="available_time_id" name="available_time_id" required>
                                    <option value="">Select Time Slot</option>
                                    @php
                                        $availableTimes = App\Models\AvailableTime::orderBy('start_time')->get();
                                    @endphp
                                    
                                    @foreach($availableTimes as $time)
                                        <option value="{{ $time->available_time_id }}" {{ old('available_time_id', $appointment->available_time_id) == $time->available_time_id ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($time->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($time->end_time)->format('H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('available_time_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('available_time_id');
        const currentAppointmentTimeId = "{{ $appointment->available_time_id }}";
        
        // Function to check availability and update time slots
        function checkAvailability() {
            const date = dateInput.value;
            if (!date) return;
            
            // Reset all options first
            for (let i = 0; i < timeSelect.options.length; i++) {
                timeSelect.options[i].disabled = false;
            }
            
            // Fetch unavailable times for the selected date
            fetch(`/admin/appointments/unavailable-times?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    // Disable unavailable time slots, except the current appointment's time
                    data.forEach(item => {
                        // Skip if this unavailable time is for the current appointment
                        // This allows keeping the current time slot selectable even if it's marked as unavailable
                        if (date === "{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}" && 
                            item.available_time_id == currentAppointmentTimeId) {
                            return;
                        }
                        
                        for (let i = 0; i < timeSelect.options.length; i++) {
                            if (timeSelect.options[i].value == item.available_time_id) {
                                timeSelect.options[i].disabled = true;
                                // If the currently selected option is now disabled, reset selection
                                if (timeSelect.selectedIndex === i) {
                                    timeSelect.selectedIndex = 0;
                                }
                            }
                        }
                    });
                });
        }
        
        // Check availability on date change
        dateInput.addEventListener('change', checkAvailability);
        
        // Initial check
        checkAvailability();
    });
</script>
@endsection