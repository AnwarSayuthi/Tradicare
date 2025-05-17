@extends('admin_layout')

@section('title', 'Create Service - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Create New Service</h4>
                        <p class="text-muted mb-0">Add a new traditional healing service to your offerings</p>
                    </div>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Services
                    </a>
                </div>
                
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
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.services.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="service_name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('service_name') is-invalid @enderror" id="service_name" name="service_name" value="{{ old('service_name') }}" required>
                                @error('service_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="duration_minutes" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="15" step="15" required>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (RM) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Service Status <span class="text-danger">*</span></label>
                                <div class="status-toggle-container p-3 border rounded-3 bg-light">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="badge {{ old('active', true) ? 'bg-success' : 'bg-secondary' }} p-2">
                                                <i class="bi {{ old('active', true) ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                                {{ old('active', true) ? 'Active (available for booking)' : 'Inactive (not available)' }}
                                            </span>
                                        </div>
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" id="active" name="active" {{ old('active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">
                                                <span class="visually-hidden">Toggle status</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Service</button>
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
    // Toggle status badge appearance when checkbox changes
    document.getElementById('active').addEventListener('change', function() {
        const statusContainer = this.closest('.status-toggle-container');
        const badge = statusContainer.querySelector('.badge');
        
        if (this.checked) {
            badge.classList.remove('bg-secondary');
            badge.classList.add('bg-success');
            badge.innerHTML = '<i class="bi bi-check-circle me-1"></i> Active (available for booking)';
        } else {
            badge.classList.remove('bg-success');
            badge.classList.add('bg-secondary');
            badge.innerHTML = '<i class="bi bi-x-circle me-1"></i> Inactive (not available)';
        }
    });
</script>
@endsection