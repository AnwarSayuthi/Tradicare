@extends('admin_layout')

@section('title', 'Edit Service - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <!-- Card Header -->
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Edit Service</h4>
                        <p class="text-muted mb-0">Update details for {{ $service->service_name }}</p>
                    </div>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Services
                    </a>
                </div>
                
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <!-- Form Section -->
                <div class="card-body p-4">
                    <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST" id="serviceForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <!-- Service Name -->
                            <div class="col-md-6 mb-3">
                                <label for="service_name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('service_name') is-invalid @enderror" 
                                       id="service_name" 
                                       name="service_name" 
                                       value="{{ old('service_name', $service->service_name) }}" 
                                       required>
                                @error('service_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Duration -->
                            <div class="col-md-6 mb-3">
                                <label for="duration_minutes" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('duration_minutes') is-invalid @enderror" 
                                       id="duration_minutes" 
                                       name="duration_minutes" 
                                       value="{{ old('duration_minutes', $service->duration_minutes) }}" 
                                       min="15" 
                                       step="15" 
                                       required>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Price -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (RM) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $service->price) }}" 
                                       min="0" 
                                       step="0.01" 
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Service Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Service Status <span class="text-danger">*</span></label>
                                <div class="status-toggle-container p-3 border rounded-3 bg-light">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="badge {{ old('active', $service->active) ? 'bg-success' : 'bg-secondary' }} p-2" id="status-badge">
                                                <i class="bi {{ old('active', $service->active) ? 'bi-check-circle' : 'bi-x-circle' }} me-1" id="status-icon"></i>
                                                <span id="status-text">{{ old('active', $service->active) ? 'Active (available for booking)' : 'Inactive (not available)' }}</span>
                                            </span>
                                        </div>
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="active" 
                                                   name="active" 
                                                   value="1" 
                                                   {{ old('active', $service->active) ? 'checked' : '' }} 
                                                   onchange="updateStatusDisplay(this)">
                                            <label class="form-check-label" for="active">
                                                <span class="visually-hidden">Toggle status</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          required>{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Status toggle functionality
    function updateStatusDisplay(checkbox) {
        const badge = document.getElementById('status-badge');
        const icon = document.getElementById('status-icon');
        const text = document.getElementById('status-text');
        
        if (checkbox.checked) {
            badge.classList.remove('bg-secondary');
            badge.classList.add('bg-success');
            icon.classList.remove('bi-x-circle');
            icon.classList.add('bi-check-circle');
            text.textContent = 'Active (available for booking)';
        } else {
            badge.classList.remove('bg-success');
            badge.classList.add('bg-secondary');
            icon.classList.remove('bi-check-circle');
            icon.classList.add('bi-x-circle');
            text.textContent = 'Inactive (not available)';
        }
    }

    // Form validation
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        const serviceNameInput = document.getElementById('service_name');
        const durationInput = document.getElementById('duration_minutes');
        const priceInput = document.getElementById('price');
        const descriptionInput = document.getElementById('description');
        
        // Service name validation
        if (serviceNameInput.value.trim() === '') {
            e.preventDefault();
            alert('Service name is required');
            serviceNameInput.focus();
            return;
        }
        
        // Duration validation
        if (durationInput.value < 15) {
            e.preventDefault();
            alert('Duration must be at least 15 minutes');
            durationInput.focus();
            return;
        }
        
        // Price validation
        if (priceInput.value < 0) {
            e.preventDefault();
            alert('Price cannot be negative');
            priceInput.focus();
            return;
        }
        
        // Description validation
        if (descriptionInput.value.trim() === '') {
            e.preventDefault();
            alert('Description is required');
            descriptionInput.focus();
            return;
        }
    });
</script>
@endsection