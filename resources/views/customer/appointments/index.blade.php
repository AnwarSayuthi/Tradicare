@extends('layout')

@section('title', 'My Appointments - Tradicare')

@section('content')
<div class="services-hero py-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">My Appointments</h1>
                <p class="lead">View and manage your scheduled wellness sessions</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Book New Appointment
        </a>
        
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item {{ request('status') == '' ? 'active' : '' }}" href="{{ route('customer.appointments.index') }}">All Appointments</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'scheduled' ? 'active' : '' }}" href="{{ route('customer.appointments.index', ['status' => 'scheduled']) }}">Scheduled</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('customer.appointments.index', ['status' => 'completed']) }}">Completed</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('customer.appointments.index', ['status' => 'cancelled']) }}">Cancelled</a></li>
                </ul>
            </div>
        
            <form action="{{ route('customer.appointments.index') }}" method="GET" class="d-flex align-items-center">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group">
                    <input type="date" class="form-control form-control-sm" id="date_filter" name="date" value="{{ request('date') }}" placeholder="Filter by date">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-calendar"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($appointments->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No Appointments Found</h5>
                <p class="text-muted">You haven't booked any appointments yet.</p>
                <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary mt-2">
                    Book Your First Appointment
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($appointments as $appointment)
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
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
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon-sm bg-primary-light rounded-circle p-3 me-3">
                                <i class="bi {{ $appointment->service->icon ?? 'bi-gem' }} text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $appointment->service->service_name }}</h5>
                                <p class="mb-0 text-muted">{{ $appointment->service->duration_minutes }} minutes</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-date text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Date</small>
                                        <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Time</small>
                                        <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($appointment->payment)
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-credit-card text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Payment</small>
                                <p class="mb-0 fw-medium">RM{{ number_format($appointment->payment->amount, 2) }} 
                                    <span class="badge bg-{{ $appointment->payment->status == 'completed' ? 'success' : ($appointment->payment->status == 'pending' ? 'warning' : 'danger') }} ms-2">
                                        {{ ucfirst($appointment->payment->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        @if($appointment->notes)
                        <div class="alert alert-light mt-3 mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <span>{{ $appointment->notes }}</span>
                        </div>
                        @endif
                        
                        <!-- Inside the appointment card, add payment button if needed -->
                        <div class="d-flex mt-4">
                            @if($appointment->status === 'scheduled')
                                <form action="{{ route('customer.appointments.cancel', $appointment->appointment_id) }}" 
                                      method="POST" class="me-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </button>
                                </form>
                                
                                <a href="{{ route('customer.appointments.reschedule', $appointment->appointment_id) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-calendar-plus me-1"></i>Reschedule
                                </a>
                            @endif
                            
                            @if(!$appointment->payment || $appointment->payment->status === 'pending')
                                <a href="{{ route('customer.appointments.payment', $appointment->appointment_id) }}" class="btn btn-primary ms-auto">
                                    <i class="bi bi-credit-card me-1"></i>Complete Payment
                                </a>
                            @endif
                        </div>
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
@endsection

@section('css')
<style>
    .fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .service-icon-sm {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }
</style>
@endsection
