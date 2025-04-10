@extends('layout')

@section('title', 'My Appointments')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Appointments</h1>
        <a href="{{ route('customer.appointment.create') }}" class="btn btn-primary">Book New Appointment</a>
    </div>

    @if($appointments->isEmpty())
        <div class="alert alert-info">
            You don't have any appointments yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->service->service_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</td>
                        <td>
                            <span class="badge bg-{{ $appointment->status === 'scheduled' ? 'primary' : ($appointment->status === 'completed' ? 'success' : 'danger') }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customer.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-info">View</a>
                            @if($appointment->status === 'scheduled')
                                <form action="{{ route('customer.appointments.cancel', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection