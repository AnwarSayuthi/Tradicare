@extends('layout')

@section('title', 'My Appointments - Tradicare')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0">My Appointments</h1>
        <a href="{{ route('customer.appointment.create') }}" class="btn btn-primary-custom">
            <i class="bi bi-plus-circle me-2"></i>Book New Appointment
        </a>
    </div>

    @if($appointments->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No Appointments Found</h5>
                <p class="text-muted">You haven't booked any appointments yet.</p>
                <a href="{{ route('customer.appointment.create') }}" class="btn btn-primary-custom mt-2">
                    Book Your First Appointment
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($appointments as $appointment)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-3 gap-2">
                            <div>
                                <h5 class="card-title mb-1">{{ $appointment->service->service_name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y g:i A') }}
                                </p>
                            </div>
                            <span class="badge bg-{{ $appointment->status === 'scheduled' ? 'primary' : ($appointment->status === 'completed' ? 'success' : 'danger') }} align-self-start">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <small class="text-muted d-block mb-1">Duration</small>
                                <p class="mb-0">{{ $appointment->service->duration_minutes }} minutes</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">End Time</small>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</p>
                            </div>
                        </div>

                        @if($appointment->notes)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Notes</small>
                            <p class="mb-0">{{ $appointment->notes }}</p>
                        </div>
                        @endif

                        @if($appointment->status === 'scheduled')
                        <div class="d-flex justify-content-end mt-3">
                            <form action="{{ route('customer.appointment.cancel', $appointment->appointment_id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="bi bi-x-circle me-1"></i>Cancel Appointment
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $appointments->links() }}
        </div>
    @endif
</div>

@section('css')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
        border-radius: 8px;
    }
    
    .pagination {
        --bs-pagination-active-bg: var(--primary);
        --bs-pagination-active-border-color: var(--primary);
    }
    
    @media (max-width: 576px) {
        .badge {
            margin-top: 0.5rem;
        }
    }
</style>
@endsection
@endsection