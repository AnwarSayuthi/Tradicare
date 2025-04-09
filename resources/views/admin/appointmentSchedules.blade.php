@extends('admin.nav')

@section('title', 'Appointment Schedule')

@section('content')
<div class="container mt-4">
    <h2>Appointment Schedule</h2>
    <div class="mb-3">
        <button class="btn btn-primary">This Month</button>
        <button class="btn btn-secondary">Week</button>
    </div>
    <div class="row">
        @foreach ($appointments as $appointment)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>{{ $appointment['title'] }}</h5>
                        <p>Time: {{ $appointment['time'] }}</p>
                        <p>Date: {{ $appointment['date'] }}</p>
                        <a href="{{ $appointment['link'] }}" target="_blank" class="btn btn-link">Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
