@extends('admin_layout')

@section('title', 'Appointment Details - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Appointment #{{ $appointment->appointment_id }}</h4>
                        <p class="text-muted mb-0">Created on {{ \Carbon\Carbon::parse($appointment->created_at)->format('M d, Y, h:i A') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Appointments
                        </a>
                        <a href="{{ route('admin.appointments.edit', $appointment->appointment_id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-8 p-4 border-end">
                            <!-- Appointment Status -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0">Appointment Status</h5>
                                    <span class="badge rounded-pill px-3 py-2 
                                        @if($appointment->status == 'scheduled') bg-warning
                                        @elseif($appointment->status == 'completed') bg-success
                                        @elseif($appointment->status == 'cancelled') bg-danger
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                
                                <div class="appointment-timeline position-relative mt-4 ps-4">
                                    @php
                                        $statuses = ['scheduled', 'completed'];
                                        $currentStatusIndex = array_search($appointment->status, $statuses);
                                        if ($currentStatusIndex === false || $appointment->status == 'cancelled') $currentStatusIndex = -1;
                                    @endphp
                                    
                                    @foreach($statuses as $index => $status)
                                        <div class="timeline-item pb-4 position-relative">
                                            <div class="timeline-icon position-absolute start-0 translate-middle-x 
                                                @if($index <= $currentStatusIndex) bg-primary @else bg-light border @endif">
                                                @if($index <= $currentStatusIndex)
                                                    <i class="bi bi-check text-white"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content ps-4">
                                                <h6 class="mb-1 @if($index <= $currentStatusIndex) fw-bold @endif">
                                                    {{ ucfirst($status) }}
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    @if($index <= $currentStatusIndex)
                                                        @if($index == $currentStatusIndex)
                                                            Current status
                                                        @else
                                                            Completed
                                                        @endif
                                                    @else
                                                        Pending
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($appointment->status == 'cancelled')
                                        <div class="timeline-item pb-0 position-relative">
                                            <div class="timeline-icon position-absolute start-0 translate-middle-x bg-danger">
                                                <i class="bi bi-x text-white"></i>
                                            </div>
                                            <div class="timeline-content ps-4">
                                                <h6 class="mb-1 fw-bold">Cancelled</h6>
                                                <p class="text-muted small mb-0">Appointment was cancelled</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Appointment Details -->
                            <h5 class="fw-semibold mb-3">Appointment Details</h5>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Service</p>
                                        <h6 class="fw-semibold mb-0">{{ $appointment->service->service_name }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Date</p>
                                        <h6 class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('M d, Y') }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Time</p>
                                        <h6 class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('h:i A') }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Duration</p>
                                        <h6 class="fw-semibold mb-0">
                                            @php
                                                $start = \Carbon\Carbon::parse($appointment->availableTime->start_time);
                                                $end = \Carbon\Carbon::parse($appointment->availableTime->end_time);
                                                $duration = $start->diffInMinutes($end);
                                            @endphp
                                            {{ $duration }} minutes
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Amount</p>
                                        <h6 class="fw-semibold mb-0">RM{{ number_format($appointment->service->price, 2) }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Payment Status</p>
                                        @if($appointment->payment)
                                            <span class="badge rounded-pill px-3 py-2 
                                                @if($appointment->payment->status == 'completed') bg-success
                                                @elseif($appointment->payment->status == 'pending') bg-warning
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($appointment->payment->status) }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-2 bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            @if($appointment->tel_number)
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <p class="text-muted mb-1">Phone Number</p>
                                        <h6 class="fw-semibold mb-0">{{ $appointment->tel_number }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Notes -->
                            <h5 class="fw-semibold mb-3">Notes</h5>
                            <div class="card bg-light border-0 rounded-3 mb-4">
                                <div class="card-body p-3">
                                    <p class="mb-0">{{ $appointment->notes ?? 'No notes available for this appointment.' }}</p>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-4">
                                @if($appointment->status == 'scheduled')
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completeModal">
                                        <i class="bi bi-check-circle me-1"></i> Mark as Completed
                                    </button>
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        <i class="bi bi-x-circle me-1"></i> Cancel Appointment
                                    </button>
                                @endif
                                <form action="{{ route('admin.appointments.destroy', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this appointment?')">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 p-4">
                            <!-- Customer Information -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3">Customer Information</h5>
                                <div class="card border-0 shadow-sm rounded-3 mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-circle bg-primary text-white me-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                {{ substr($appointment->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $appointment->user->name }}</h6>
                                                <p class="text-muted mb-0">{{ $appointment->user->email }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <p class="text-muted mb-1 small">Phone</p>
                                            <p class="mb-0">{{ $appointment->user->tel_number ?? 'Not provided' }}</p>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.customers.show', $appointment->user->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="bi bi-person me-1"></i> View Customer Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Appointment History -->
                            <div>
                                <h5 class="fw-semibold mb-3">Customer's Appointment History</h5>
                                @php
                                    $pastAppointments = \App\Models\Appointment::where('user_id', $appointment->user_id)
                                        ->where('appointment_id', '!=', $appointment->appointment_id)
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                
                                @if($pastAppointments->count() > 0)
                                    <div class="list-group shadow-sm rounded-3">
                                        @foreach($pastAppointments as $pastAppointment)
                                            <a href="{{ route('admin.appointments.show', $pastAppointment->appointment_id) }}" class="list-group-item list-group-item-action border-0 py-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">{{ $pastAppointment->service->service_name }}</h6>
                                                        <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($pastAppointment->created_at)->format('M d, Y, h:i A') }}</p>
                                                    </div>
                                                    <span class="badge rounded-pill px-3 py-2 
                                                        @if($pastAppointment->status == 'scheduled') bg-warning
                                                        @elseif($pastAppointment->status == 'completed') bg-success
                                                        @elseif($pastAppointment->status == 'cancelled') bg-danger
                                                        @endif">
                                                        {{ ucfirst($pastAppointment->status) }}
                                                    </span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="card bg-light border-0 rounded-3">
                                        <div class="card-body p-3 text-center">
                                            <p class="mb-0">No previous appointments found for this customer.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Complete Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.appointments.update-status', $appointment->appointment_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <input type="hidden" name="status" value="completed">
                    <p>Are you sure you want to mark this appointment as completed?</p>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this appointment">{{ $appointment->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Complete Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.appointments.update-status', $appointment->appointment_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <input type="hidden" name="status" value="cancelled">
                    <p>Are you sure you want to cancel this appointment?</p>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Reason for Cancellation</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Provide a reason for cancellation" required>{{ $appointment->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .timeline-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 30px;
        left: 0;
        transform: translateX(-50%);
        width: 2px;
        height: calc(100% - 30px);
        background-color: #e9ecef;
        z-index: 0;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .list-group-item:first-child {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .list-group-item:last-child {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    
    .modal-content {
        border-radius: 0.5rem;
    }
    
    .btn {
        border-radius: 0.375rem;
    }
</style>
@endsection