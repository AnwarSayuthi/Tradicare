@extends('admin_layout')

@section('title', 'Service Details - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Service Details</h4>
                        <p class="text-muted mb-0">Viewing details for {{ $service->service_name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.services.edit', $service->service_id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Service
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Services
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="service-details bg-white p-4 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="service-icon me-3">
                                        <i class="bi {{ $service->icon ?? 'bi-diamond-fill' }}"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-1">{{ $service->service_name }}</h3>
                                        <span class="status-badge {{ $service->active ? 'active' : 'inactive' }}">
                                            {{ $service->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <div class="stat-card bg-light p-3 rounded-3">
                                            <small class="text-muted d-block mb-1">Category</small>
                                            <h5 class="mb-0">{{ ucfirst($service->category) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card bg-light p-3 rounded-3">
                                            <small class="text-muted d-block mb-1">Duration</small>
                                            <h5 class="mb-0">{{ $service->duration_minutes }} mins</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card bg-light p-3 rounded-3">
                                            <small class="text-muted d-block mb-1">Price</small>
                                            <h5 class="mb-0">RM {{ number_format($service->price, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="mb-3">Description</h5>
                                    <div class="p-3 bg-light rounded-3">
                                        <p class="mb-0">{{ $service->description }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="mb-3">Service Status</h5>
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('admin.services.toggle-status', $service->service_id) }}" method="POST" class="me-3">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-{{ $service->active ? 'warning' : 'success' }}">
                                                <i class="bi bi-toggle-{{ $service->active ? 'on' : 'off' }} me-2"></i>
                                                {{ $service->active ? 'Deactivate' : 'Activate' }} Service
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.services.destroy', $service->service_id) }}" method="POST" class="d-inline service-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash me-2"></i>Delete Service
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-light border-0">
                                    <h5 class="mb-0">Service Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Total Appointments</h6>
                                        <p class="fs-4 mb-0">{{ $service->appointments()->count() }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Created On</h6>
                                        <p class="mb-0">{{ $service->created_at ? $service->created_at->format('d M Y, h:i A') : 'Not available' }}</p>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-2">Last Updated</h6>
                                        <p class="mb-0">{{ $service->updated_at ? $service->updated_at->format('d M Y, h:i A') : 'Not available' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-light border-0">
                                    <h5 class="mb-0">Customer Preview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="service-preview p-3 border rounded-3">
                                        <div class="text-center mb-3">
                                            <div class="service-icon mx-auto rounded-circle bg-primary bg-opacity-10 p-3 text-center" style="width: 70px; height: 70px;">
                                                <i class="bi {{ $service->icon ?? 'bi-gem' }} fs-3 text-primary"></i>
                                            </div>
                                            <h5 class="mt-3 mb-1">{{ $service->service_name }}</h5>
                                            <p class="text-muted small mb-2">{{ ucfirst($service->category) }}</p>
                                            <p class="fw-bold mb-0">RM {{ number_format($service->price, 2) }}</p>
                                        </div>
                                        <div class="small">
                                            <p class="mb-2"><i class="bi bi-clock me-2"></i>{{ $service->duration_minutes }} minutes</p>
                                            <p class="mb-0 text-truncate">{{ Str::limit($service->description, 100) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">This is how customers will see your service</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Confirm delete
    document.querySelector('.service-delete-form').addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
</script>
@endsection