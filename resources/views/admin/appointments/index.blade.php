@extends('admin_layout')

@section('title', 'Appointments - Tradicare Admin')

@section('content')
<style>
/* Enhanced CSS for better appearance - Following orders page styling */
.appointment-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    table-layout: fixed;
    width: 100%;
}

.appointment-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.appointment-table thead th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-transform: uppercase;
    font-size: 0.8rem;
}

.appointment-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.appointment-table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.appointment-table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Column widths */
.appointment-table th:nth-child(1),
.appointment-table td:nth-child(1) { width: 8%; }
.appointment-table th:nth-child(2),
.appointment-table td:nth-child(2) { width: 22%; }
.appointment-table th:nth-child(3),
.appointment-table td:nth-child(3) { width: 18%; }
.appointment-table th:nth-child(4),
.appointment-table td:nth-child(4) { width: 15%; }
.appointment-table th:nth-child(5),
.appointment-table td:nth-child(5) { width: 12%; }
.appointment-table th:nth-child(6),
.appointment-table td:nth-child(6) { width: 10%; }
.appointment-table th:nth-child(7),
.appointment-table td:nth-child(7) { width: 15%; }

.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
}

.status-badge {
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid transparent;
    display: inline-block;
    transition: all 0.3s ease;
}

.status-to-pay {
    background: linear-gradient(135deg, #fdcb6e, #e17055);
    color: white;
    border-color: #fdcb6e;
}

.status-scheduled {
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    color: white;
    border-color: #74b9ff;
}

.status-cancelled {
    background: linear-gradient(135deg, #fd79a8, #e84393);
    color: white;
    border-color: #fd79a8;
}

.status-completed {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    border-color: #00b894;
}

.payment-badge {
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.payment-pending {
    background: linear-gradient(135deg, #fdcb6e, #e17055);
    color: white;
    border-color: #fdcb6e;
}

.payment-complete {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    border-color: #00b894;
}

.btn-action {
    border-radius: 10px;
    padding: 8px 12px;
    margin: 0 2px;
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
    background: white;
    font-weight: 500;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    background: #f7fafc;
    border-color: #cbd5e0;
}

/* Enhanced stats cards layout - 4 cards in one row */
.stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2.5rem;
    padding: 0 0.5rem;
}

.stats-card {
    border-radius: 16px;
    border: none;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    height: 100%;
    min-width: 0;
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.stats-card .card-body {
    padding: 2rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    height: 100%;
}

.stats-card .icon-container {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.stats-card h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
}

.stats-card h2 {
    font-size: 2.25rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
    margin: 0;
}

/* Enhanced filter section - Full width optimized layout */
.filter-card {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.filter-card .card-body {
    padding: 2rem 2.5rem;
}

.filter-card .row {
    align-items: end;
    gap: 0;
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}

.filter-card .col-12,
.filter-card .col-md-4,
.filter-card .col-md-3,
.filter-card .col-md-2 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.filter-card .form-floating {
    margin-bottom: 0;
}

.filter-card .form-floating > .form-control,
.filter-card .form-floating > .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    background: white;
    height: 58px;
    font-size: 0.95rem;
    padding: 1rem 0.75rem 0.25rem 0.75rem;
}

.filter-card .form-floating > label {
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.filter-card .form-floating > .form-control:focus,
.filter-card .form-floating > .form-select:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
}

.filter-card .form-floating > .form-control:focus ~ label,
.filter-card .form-floating > .form-select:focus ~ label,
.filter-card .form-floating > .form-control:not(:placeholder-shown) ~ label,
.filter-card .form-floating > .form-select:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    color: #667eea;
}

.filter-card .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    height: 58px;
    font-size: 0.95rem;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    min-width: 130px;
}

.filter-card .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
}

.filter-card .btn-primary i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

/* Color coding for 4 card types - Updated order */
.stats-card:nth-child(1) .icon-container {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
}

.stats-card:nth-child(1) .icon-container i {
    color: #1976d2;
}

.stats-card:nth-child(2) .icon-container {
    background: linear-gradient(135deg, #fff3e0, #ffcc02);
}

.stats-card:nth-child(2) .icon-container i {
    color: #f57c00;
}

.stats-card:nth-child(3) .icon-container {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
}

.stats-card:nth-child(3) .icon-container i {
    color: #388e3c;
}

.stats-card:nth-child(4) .icon-container {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
}

.stats-card:nth-child(4) .icon-container i {
    color: #d32f2f;
}

/* Enhanced pagination styling */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    border-radius: 10px;
    margin: 0 3px;
    border: 2px solid #e2e8f0;
    color: #667eea;
    font-weight: 500;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #f8f9ff, #e9ecef);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.pagination .page-item.disabled .page-link {
    color: #cbd5e0;
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

/* Empty state styling */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state i {
    font-size: 5rem;
    color: #cbd5e0;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.empty-state h5 {
    color: #4a5568;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.empty-state p {
    color: #718096;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.empty-state .btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empty-state .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Main card styling */
.main-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.main-card .card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    border-radius: 16px 16px 0 0;
}

/* Customer info styling */
.customer-info {
    white-space: normal;
}

.customer-info h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
    color: #2d3748;
}

.customer-info small {
    display: block;
    line-height: 1.3;
    color: #718096;
}

/* Service info styling */
.service-info h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
    color: #2d3748;
}

.service-info small {
    color: #718096;
    font-weight: 500;
}

/* Date time styling */
.datetime-info h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
    color: #2d3748;
}

.datetime-info small {
    color: #718096;
}

/* Enhanced button styling */
.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Responsive design optimizations */
@media (max-width: 1200px) {
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
    
    .stats-card .card-body {
        padding: 1.75rem 1.25rem;
    }
    
    .stats-card .icon-container {
        width: 65px;
        height: 65px;
        margin-right: 1.25rem;
    }
    
    .stats-card h2 {
        font-size: 2rem;
    }
}

@media (max-width: 992px) {
    .filter-card .card-body {
        padding: 1.75rem 2rem;
    }
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stats-card .card-body {
        padding: 1.5rem 1.25rem;
        flex-direction: row;
        text-align: left;
    }
    
    .stats-card .icon-container {
        width: 60px;
        height: 60px;
        margin-right: 1rem;
    }
    
    .filter-card .card-body {
        padding: 1.5rem;
    }
    
    .filter-card .row {
        row-gap: 1rem;
    }
}

@media (max-width: 576px) {
    .stats-card .card-body {
        padding: 1.25rem 1rem;
    }
    
    .stats-card .icon-container {
        width: 55px;
        height: 55px;
        margin-right: 0.75rem;
    }
    
    .stats-card h6 {
        font-size: 0.8rem;
    }
    
    .stats-card h2 {
        font-size: 1.75rem;
    }
    
    .filter-card .card-body {
        padding: 1.25rem;
    }
    
    .appointment-table thead th:nth-child(4),
    .appointment-table tbody td:nth-child(4),
    .appointment-table thead th:nth-child(6),
    .appointment-table tbody td:nth-child(6) {
        display: none;
    }
}

/* Additional visual enhancements */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
}

.card-footer {
    background: linear-gradient(135deg, #f8f9fa, #ffffff) !important;
    border-top: 1px solid #e9ecef;
}

/* Enhanced dropdown styling */
.dropdown-menu {
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    padding: 0.5rem 0;
}

.dropdown-item {
    padding: 0.75rem 1.25rem;
    transition: all 0.2s ease;
    font-weight: 500;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9ff, #e9ecef);
    transform: translateX(4px);
}

.dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #d32f2f !important;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Appointment Management</h1>
        
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
    <div class="stats-container">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="bi bi-calendar-check fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalAppointments }}</h2>
                </div>
            </div>
        </div>
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="bi bi-credit-card fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">To Pay</h6>
                    <h2 class="mb-0 fw-bold">{{ $toPayAppointments }}</h2>
                </div>
            </div>
        </div>
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="bi bi-clock fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Scheduled</h6>
                    <h2 class="mb-0 fw-bold">{{ $scheduledAppointments }}</h2>
                </div>
            </div>
        </div>
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="bi bi-x-circle fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Cancelled</h6>
                    <h2 class="mb-0 fw-bold">{{ $cancelledAppointments }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Appointments Card -->
    <div class="card main-card border-0">       
        <!-- Filters -->
            <div class="card filter-card shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.appointments.index') }}" method="GET" class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="{{ request('search') }}">
                                <label for="search">Search by name, email or ID</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>To Pay</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                                <label for="date">Date</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-center">
                            <button type="submit" class="btn btn-primary w-100 py-3">
                                <i class="bi bi-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 appointment-table">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-4">ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Service</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Payment</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $index => $appointment)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-medium">{{ ($appointments->currentPage() - 1) * $appointments->perPage() + $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center customer-info">
                                        <div class="avatar-circle text-white me-3">
                                            {{ substr($appointment->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $appointment->user->name }}</h6>
                                            <small class="text-muted">{{ $appointment->user->email }}</small>
                                            @if($appointment->tel_number)
                                                <small class="text-muted">{{ $appointment->tel_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="service-info">
                                        <h6 class="mb-0">{{ $appointment->service->service_name }}</h6>
                                        <small class="text-muted">RM{{ number_format($appointment->service->price, 2) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="datetime-info">
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</h6>
                                        @if($appointment->availableTime)
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->availableTime->start_time)->format('h:i A') }}</small>
                                        @else
                                            <small class="text-muted text-danger">Time slot deleted</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        
                                        if (!$appointment->payment || $appointment->payment->status == 'pending') {
                                            $statusClass = 'status-to-pay';
                                            $statusText = 'To Pay';
                                        } elseif ($appointment->status == 'scheduled') {
                                            $statusClass = 'status-scheduled';
                                            $statusText = 'Scheduled';
                                        } elseif ($appointment->status == 'cancelled') {
                                            $statusClass = 'status-cancelled';
                                            $statusText = 'Cancelled';
                                        } elseif ($appointment->status == 'completed') {
                                            $statusClass = 'status-completed';
                                            $statusText = 'Completed';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    @if($appointment->payment)
                                        @php
                                            $paymentClass = $appointment->payment->status == 'completed' ? 'payment-complete' : 'payment-pending';
                                            $paymentText = $appointment->payment->status == 'completed' ? 'Complete' : 'Pending';
                                        @endphp
                                        <span class="payment-badge {{ $paymentClass }}">
                                            {{ $paymentText }}
                                        </span>
                                    @else
                                        <span class="payment-badge payment-pending">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group" aria-label="Appointment actions">
                                        <a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}" 
                                           class="btn btn-sm btn-outline-primary btn-action" 
                                           title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.edit', $appointment->appointment_id) }}" 
                                           class="btn btn-sm btn-outline-secondary btn-action" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-success btn-action" 
                                                data-action="complete"
                                                data-appointment-id="{{ $appointment->appointment_id }}"
                                                data-appointment-notes="{{ $appointment->notes }}"
                                                title="Mark as Completed">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning btn-action" 
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
                                                    class="btn btn-sm btn-outline-danger btn-action" 
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal handling for complete and cancel actions
document.addEventListener('DOMContentLoaded', function() {
    const completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
    const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
    
    document.querySelectorAll('[data-action="complete"]').forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.dataset.appointmentId;
            const notes = this.dataset.appointmentNotes;
            
            document.getElementById('completeForm').action = `/admin/appointments/${appointmentId}`;
            document.getElementById('complete-notes').value = notes || '';
            
            completeModal.show();
        });
    });
    
    document.querySelectorAll('[data-action="cancel"]').forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.dataset.appointmentId;
            const notes = this.dataset.appointmentNotes;
            
            document.getElementById('cancelForm').action = `/admin/appointments/${appointmentId}`;
            document.getElementById('cancel-notes').value = notes || '';
            
            cancelModal.show();
        });
    });
});
</script>
@endsection