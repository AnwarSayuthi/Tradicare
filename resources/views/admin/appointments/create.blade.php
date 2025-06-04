@extends('admin_layout')

@section('title', 'Create Appointment - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Create New Appointment</h4>
                        <p class="text-muted mb-0">Schedule a new appointment for a customer</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.appointments.times.manage') }}" class="btn btn-outline-primary">
                            <i class="bi bi-clock me-1"></i> Manage Time Slots
                        </a>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Appointments
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.appointments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Customer</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
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
                                        <option value="{{ $service->service_id }}" {{ old('service_id') == $service->service_id ? 'selected' : '' }}>
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
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d') }}" required>
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
                                        <option value="{{ $time->available_time_id }}" {{ old('available_time_id') == $time->available_time_id ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($time->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($time->end_time)->format('H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('available_time_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($availableTimes->count() == 0)
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i> No time slots available. <a href="{{ route('admin.appointments.times.manage') }}">Create time slots</a> first.
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" @if($availableTimes->count() == 0) disabled @endif>Create Appointment</button>
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
        
        // Function to check availability and update time slots
        function checkAvailability() {
            const date = dateInput.value;
            if (!date) return;
            
            // Disable all options first
            for (let i = 0; i < timeSelect.options.length; i++) {
                timeSelect.options[i].disabled = false;
            }
            
            // Fetch unavailable times for the selected date
            fetch(`/admin/appointments/unavailable-times?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    // Disable unavailable time slots
                    data.forEach(item => {
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