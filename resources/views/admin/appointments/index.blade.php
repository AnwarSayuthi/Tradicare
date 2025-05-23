@extends('admin_layout')

@section('title', 'Appointments - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Appointments</h1>
        
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> New Appointment
            </a>
        </div>
    </div>
    
    <!-- Appointment Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3 bg-light rounded-circle p-3">
                        <i class="bi bi-calendar-check fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalAppointments }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3 bg-light rounded-circle p-3">
                        <i class="bi bi-clock fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Scheduled</h6>
                        <h2 class="mb-0 fw-bold">{{ $scheduledAppointments }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-sm-0">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3 bg-light rounded-circle p-3">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Completed</h6>
                        <h2 class="mb-0 fw-bold">{{ $completedAppointments }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3 bg-light rounded-circle p-3">
                        <i class="bi bi-x-circle fs-3 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Cancelled</h6>
                        <h2 class="mb-0 fw-bold">{{ $cancelledAppointments }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.appointments.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="{{ request('search') }}">
                        <label for="search">Search by name, email or ID</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <label for="status">Status</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                        <label for="date">Date</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Appointments Table -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="ps-4">ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Service</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-medium">#{{ $appointment->appointment_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            {{ substr($appointment->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $appointment->user->name }}</h6>
                                            <small class="text-muted">{{ $appointment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $appointment->service->service_name }}</td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</h6>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 
                                        @if($appointment->status == 'scheduled') bg-warning
                                        @elseif($appointment->status == 'completed') bg-success
                                        @elseif($appointment->status == 'cancelled') bg-danger
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.appointments.show', $appointment->appointment_id) }}">
                                                    <i class="bi bi-eye me-2"></i> View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.appointments.edit', $appointment->appointment_id) }}">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#completeModal-{{ $appointment->appointment_id }}">
                                                    <i class="bi bi-check-circle me-2"></i> Mark as Completed
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $appointment->appointment_id }}">
                                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.appointments.destroy', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this appointment?')">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- Complete Modal -->
                                    <div class="modal fade" id="completeModal-{{ $appointment->appointment_id }}" tabindex="-1" aria-hidden="true">
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
                                    <div class="modal fade" id="cancelModal-{{ $appointment->appointment_id }}" tabindex="-1" aria-hidden="true">
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                                        <h5 class="fw-medium mb-1">No Appointments Found</h5>
                                        <p class="text-muted mb-3">There are no appointments matching your criteria.</p>
                                        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Create New Appointment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($appointments->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $appointments->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
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
    
    .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
    }
    
    .dropdown-item i {
        width: 1rem;
        text-align: center;
    }
    
    .modal-content {
        border-radius: 0.5rem;
    }
    
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(3.5rem + 2px);
    }
    
    .form-floating > label {
        padding: 1rem 0.75rem;
    }
    
    .btn {
        border-radius: 0.375rem;
    }
</style>
@endsection