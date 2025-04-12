@extends('layout')

@section('title', 'Book Appointment')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 text-primary-custom">Book Your Appointment</h2>
                    
                    <form action="{{ route('customer.appointment.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="service_id" class="form-label">Select Service</label>
                            <select class="form-control" id="service_id" name="service_id" required>
                                <option value="">Choose a service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->service_id }}" 
                                            data-duration="{{ $service->duration_minutes }}"
                                            data-price="{{ $service->price }}">
                                        {{ $service->service_name }} - {{ $service->duration_minutes }} mins (RM{{ number_format($service->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="appointment_date" class="form-label">Preferred Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                        </div>

                        <div class="mb-4">
                            <label for="appointment_time" class="form-label">Preferred Time</label>
                            <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Special Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                    placeholder="Any special requests or concerns?"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .btn-primary-custom {
        background-color: #493628;
        border-color: #493628;
        color: white;
    }
    .btn-primary-custom:hover {
        background-color: #5a442f;
        border-color: #5a442f;
        color: white;
    }
    .text-primary-custom {
        color: #493628;
    }
    .form-control:focus {
        border-color: #D6C0B3;
        box-shadow: 0 0 0 0.25rem rgba(214, 192, 179, 0.25);
    }
</style>
@endsection