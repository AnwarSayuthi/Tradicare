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
                                        <i class="bi bi-gem text-dark"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-1">{{ $service->service_name }}</h3>
                                        <span class="badge {{ $service->active ? 'bg-success' : 'bg-secondary' }} p-2">
                                            <i class="bi {{ $service->active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                            {{ $service->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="stat-card bg-light p-3 rounded-3">
                                            <small class="text-muted d-block mb-1">Duration</small>
                                            <h5 class="mb-0">{{ $service->duration_minutes }} mins</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                
                                <div class="service-actions mt-4">
                                    <h5 class="mb-3">Service Status</h5>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                            <i class="bi bi-toggle-on me-2"></i>Update Status
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteServiceModal">
                                            <i class="bi bi-trash me-2"></i>Delete Service
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">        
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-light border-0">
                                    <h5 class="mb-0">Customer Preview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="service-preview p-3 border rounded-3">
                                        <div class="text-center mb-3">
                                            <div class="service-icon mx-auto rounded-circle bg-light p-3 text-center" style="width: 70px; height: 70px;">
                                                <i class="bi bi-gem fs-3 text-dark"></i>
                                            </div>
                                            <h5 class="mt-3 mb-1">{{ $service->service_name }}</h5>
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

<!-- Delete Service Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="deleteServiceModalLabel">
                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Delete Service
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger bg-danger-subtle border-0">
                    <p class="mb-0">Are you sure you want to delete service "<strong>{{ $service->service_name }}</strong>"?</p>
                    <p class="mb-0 mt-2">This service will be hidden from customers, but all past appointments will be preserved.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.services.destroy', $service->service_id) }}" method="POST" class="service-delete-form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Delete Service</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-toggle-on me-2 text-primary"></i>Update Service Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.services.toggle-status', $service->service_id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <p class="text-muted">Change status for: <strong>{{ $service->service_name }}</strong></p>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-medium">Status</label>
                        <select class="form-select form-select-lg" id="status" name="active">
                            <option value="1" {{ $service->active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$service->active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update Status</button>
                </div>
            </form>
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