@extends('admin_layout')

@section('title', 'Edit Service - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Edit Service</h4>
                        <p class="text-muted mb-0">Update details for {{ $service->service_name }}</p>
                    </div>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Services
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="service_name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('service_name') is-invalid @enderror" id="service_name" name="service_name" value="{{ old('service_name', $service->service_name) }}" required>
                                @error('service_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $service->category) }}" required>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="duration_minutes" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" min="15" step="15" required>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price (RM) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="icon" class="form-label">Icon (Bootstrap Icons)</label>
                                <div class="input-group">
                                    <span class="input-group-text">bi-</span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', str_replace('bi-', '', $service->icon)) }}" placeholder="e.g. gem, heart, star">
                                </div>
                                <small class="text-muted">Visit <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> for icon names</small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" {{ old('active', $service->active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">Active (available for booking)</label>
                                </div>
                            </div>
                        </div>
                        
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
    // Preview icon as user types
    document.getElementById('icon').addEventListener('input', function() {
        const iconName = this.value.trim();
        const previewIcon = document.querySelector('.input-group-text');
        
        if (iconName) {
            previewIcon.innerHTML = `<i class="bi bi-${iconName}"></i>`;
        } else {
            previewIcon.innerHTML = 'bi-';
        }
    });
    
    // Initialize icon preview
    window.addEventListener('DOMContentLoaded', function() {
        const iconName = document.getElementById('icon').value.trim();
        const previewIcon = document.querySelector('.input-group-text');
        
        if (iconName) {
            previewIcon.innerHTML = `<i class="bi bi-${iconName}"></i>`;
        }
    });
</script>
@endsection