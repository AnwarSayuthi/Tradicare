@extends('customer.nav')

@section('title', 'Booking Appointment')

@section('content')
<div class="container mt-5">
    <!-- Page Title -->
    <div class="text-center">
        <h1 class="fw-bold">Booking Appointment</h1>
        <p>
            We provide a convenient online booking appointment system. Simply select your desired date, time, and party size, and we will make sure that your masseuse is ready upon your arrival.
        </p>
    </div>

    <!-- Appointment Form -->
    <form action="{{ route('customer.appointment.store') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="guests" class="form-label">Guests</label>
                <select class="form-control" id="guests" name="guests" required>
                    <option value="" disabled selected>Number of guests</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="treatment_type" class="form-label">Treatment Type</label>
                <input type="text" class="form-control" id="treatment_type" name="treatment_type" placeholder="Which part of body?" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-dark w-50">Book Now</button>
        </div>
    </form>
</div>
@endsection
