@extends('admin_layout')

@section('title', 'Appointments - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Appointments</h1>
        
        <!-- In the header section, update the buttons -->
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.appointments.times.manage') }}" class="btn btn-outline-primary">
                <i class="bi bi-clock me-1"></i> Manage Time Slots
            </a>
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
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive ">
                <table class="table table-hover align-middle mb-0 ">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="ps-4">ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Service</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Payment</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-4 ">Actions</th>
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
                                            @if($appointment->tel_number)
                                                <br><small class="text-muted">{{ $appointment->tel_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $appointment->service->service_name }}</h6>
                                        <small class="text-muted">RM{{ number_format($appointment->service->price, 2) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</h6>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($appointment->payment)
                                        <span class="badge rounded-pill px-2 py-1 
                                            @if($appointment->payment->status == 'completed') bg-success
                                            @elseif($appointment->payment->status == 'pending') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($appointment->payment->status) }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2 py-1 bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group" aria-label="Appointment actions">
                                        <a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.edit', $appointment->appointment_id) }}" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-success" 
                                                data-action="complete"
                                                data-appointment-id="{{ $appointment->appointment_id }}"
                                                data-appointment-notes="{{ $appointment->notes }}"
                                                title="Mark as Completed">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" 
                                                data-action="cancel"
                                                data-appointment-id="{{ $appointment->appointment_id }}"
                                                data-appointment-notes="{{ $appointment->notes }}"
                                                title="Cancel">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        <form action="{{ route('admin.appointments.destroy', $appointment->appointment_id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this appointment?')"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
    <!-- Reusable Complete Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="completeForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <input type="hidden" name="status" value="completed">
                        <p>Are you sure you want to mark this appointment as completed?</p>
                        <div class="mb-3">
                            <label for="complete-notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="complete-notes" name="notes" rows="3" placeholder="Add any notes about this appointment"></textarea>
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
    
    <!-- Reusable Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <input type="hidden" name="status" value="cancelled">
                        <p>Are you sure you want to cancel this appointment?</p>
                        <div class="mb-3">
                            <label for="cancel-notes" class="form-label">Reason for Cancellation</label>
                            <textarea class="form-control" id="cancel-notes" name="notes" rows="3" placeholder="Provide a reason for cancellation" required></textarea>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        const completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
        const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
        
        // Get form elements
        const completeForm = document.getElementById('completeForm');
        const cancelForm = document.getElementById('cancelForm');
        
        // Get notes fields
        const completeNotes = document.getElementById('complete-notes');
        const cancelNotes = document.getElementById('cancel-notes');
        
        // Add event listeners to all action buttons
        document.querySelectorAll('[data-action]').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const appointmentId = this.getAttribute('data-appointment-id');
                const appointmentNotes = this.getAttribute('data-appointment-notes') || '';
                
                // Fix: Use the proper route with the ID parameter
                const formActionUrl = `{{ url('/admin/appointments') }}/${appointmentId}/status`;
                
                if (action === 'complete') {
                    completeForm.action = formActionUrl;
                    completeNotes.value = appointmentNotes;
                    completeModal.show();
                } else if (action === 'cancel') {
                    cancelForm.action = formActionUrl;
                    cancelNotes.value = appointmentNotes;
                    cancelModal.show();
                }
            });
        });
    });
</script>
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
    
    /* Enhanced button group styles */
    .btn-group .btn {
        border-radius: 0;
        border-right: 1px solid rgba(0, 0, 0, 0.125);
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
        border-right: 1px solid;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }
    
    .btn-group .btn:focus {
        z-index: 2;
    }
    
    /* Responsive button group */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem !important;
            border-right: 1px solid;
            margin-bottom: 2px;
            width: 100%;
        }
        
        .btn-group .btn:last-child {
            margin-bottom: 0;
        }
    }
    
    /* Table responsive improvements */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
    
    .table-hover > tbody > tr:hover > * {
        background-color: rgba(0, 123, 255, 0.05);
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
    
    /* Tooltip enhancement */
    [title] {
        position: relative;
    }
</style>
@endsection
