@extends('admin_layout')

@section('title', 'Manage Appointment Times - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Manage Appointment Times</h1>
        
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Appointments
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Create Available Time Slots -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0">
                    <h4 class="mb-1 fw-bold">Create Available Time Slots</h4>
                    <p class="text-muted mb-0">Define standard time slots for appointments</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.appointments.times.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium">Select Time Slots</label>
                            <div class="row g-3">
                                @php
                                    $timeSlots = [
                                        '00:00' => '00:00 - 01:00',
                                        '01:00' => '01:00 - 02:00',
                                        '02:00' => '02:00 - 03:00',
                                        '03:00' => '03:00 - 04:00',
                                        '04:00' => '04:00 - 05:00',
                                        '05:00' => '05:00 - 06:00',
                                        '06:00' => '06:00 - 07:00',
                                        '07:00' => '07:00 - 08:00',
                                        '08:00' => '08:00 - 09:00',
                                        '09:00' => '09:00 - 10:00',
                                        '10:00' => '10:00 - 11:00',
                                        '11:00' => '11:00 - 12:00',
                                        '12:00' => '12:00 - 13:00',
                                        '13:00' => '13:00 - 14:00',
                                        '14:00' => '14:00 - 15:00',
                                        '15:00' => '15:00 - 16:00',
                                        '16:00' => '16:00 - 17:00',
                                        '17:00' => '17:00 - 18:00',
                                        '18:00' => '18:00 - 19:00',
                                        '19:00' => '19:00 - 20:00',
                                        '20:00' => '20:00 - 21:00',
                                        '21:00' => '21:00 - 22:00',
                                        '22:00' => '22:00 - 23:00',
                                        '23:00' => '23:00 - 00:00'
                                    ];
                                @endphp
                                
                                @foreach($timeSlots as $value => $label)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check custom-checkbox">
                                            <input class="form-check-input" type="checkbox" name="time_slots[]" value="{{ $value }}" id="slot-{{ $value }}">
                                            <label class="form-check-label" for="slot-{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Create Time Slots
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mark Unavailable Dates -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0">
                    <h4 class="mb-1 fw-bold">Mark Unavailable Dates</h4>
                    <p class="text-muted mb-0">Block specific dates or time slots</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.appointments.unavailable.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="unavailable_date" class="form-label fw-medium">Select Date</label>
                            <input type="date" class="form-control" id="unavailable_date" name="date" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium">Select Time Slots to Block</label>
                            <div class="row g-3">
                                @php
                                    $availableTimes = App\Models\AvailableTime::all();
                                @endphp
                                
                                @if($availableTimes->count() > 0)
                                    @foreach($availableTimes as $time)
                                        <div class="col-md-6">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox" name="available_time_ids[]" value="{{ $time->available_time_id }}" id="time-{{ $time->available_time_id }}">
                                                <label class="form-check-label" for="time-{{ $time->available_time_id }}">
                                                    {{ \Carbon\Carbon::parse($time->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($time->end_time)->format('H:i') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i> No time slots available. Please create time slots first.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger" @if($availableTimes->count() == 0) disabled @endif>
                                <i class="bi bi-calendar-x me-1"></i> Block Selected Times
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Current Available Time Slots -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold">Available Time Slots</h4>
                        <p class="text-muted mb-0">Currently available appointment time slots</p>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4">ID</th>
                                    <th scope="col">Time Slot</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($availableTimes as $time)
                                    <tr>
                                        <td class="ps-4">#{{ $time->available_time_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($time->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($time->end_time)->format('H:i') }}</td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('admin.appointments.times.destroy', $time->available_time_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this time slot?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <p class="text-muted mb-0">No available time slots found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Unavailable Dates -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold">Unavailable Dates</h4>
                        <p class="text-muted mb-0">Dates and times that are blocked for appointments</p>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4">Date</th>
                                    <th scope="col">Time Slot</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- In the Unavailable Dates section, modify the query to exclude trashed items -->
                                @php
                                    $unavailableTimes = App\Models\UnavailableTime::with('availableTime')
                                        ->whereNull('deleted_at')
                                        ->orderBy('date', 'desc')
                                        ->get();
                                @endphp
                                
                                @forelse($unavailableTimes as $unavailable)
                                <tr>
                                    <td class="ps-4">{{ \Carbon\Carbon::parse($unavailable->date)->format('M d, Y') }}</td>
                                    <td>
                                        @if($unavailable->availableTime)
                                            {{ \Carbon\Carbon::parse($unavailable->availableTime->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($unavailable->availableTime->end_time)->format('H:i') }}
                                        @else
                                            <span class="text-muted">Time slot deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">Active</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.appointments.unavailable.destroy', $unavailable->unavailable_time_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this unavailable time?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-muted mb-0">No unavailable dates found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .custom-checkbox .form-check-input {
        width: 1.2em;
        height: 1.2em;
    }
    
    .custom-checkbox .form-check-label {
        padding-left: 0.5rem;
    }
</style>
@endsection