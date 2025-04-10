@extends('layout')

@section('title', 'Book Appointment')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <h1 class="fw-bold">Book Appointment</h1>
        <p>Choose from our selection of services and book your appointment today.</p>
    </div>

    <form action="{{ route('customer.appointment.store') }}" method="POST" class="mt-4">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="service_id" class="form-label">Service</label>
                <select class="form-control" id="service_id" name="service_id" required>
                    <option value="" disabled selected>Select a service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->service_id }}" data-duration="{{ $service->duration_minutes }}" data-price="{{ $service->price }}">
                            {{ $service->service_name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} mins)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="appointment_date" class="form-label">Date & Time</label>
                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Special Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special requests or notes?"></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary w-50">Book Appointment</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Disable past dates and times
    const appointmentDateInput = document.getElementById('appointment_date');
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    appointmentDateInput.min = now.toISOString().slice(0, 16);
</script>
@endpush
@endsection