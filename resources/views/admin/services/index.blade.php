@extends('admin_layout')

@section('title', 'Manage Services - Tradicare Admin')

@section('page-title', 'Services')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Services</h1>
        
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.reports.generate', 'services') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export
            </a>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Service
            </a>
        </div>
    </div>
    
    <!-- Service Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-gem fs-1 text-dark"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">All Services</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalServices }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Active Services</h6>
                        <h2 class="mb-0 fw-bold">{{ $activeServices }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-x-circle fs-1 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Inactive Services</h6>
                        <h2 class="mb-0 fw-bold">{{ $inactiveServices }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Manage Services</h4>
                        <p class="text-muted mb-0">Create, update and manage your traditional healing services</p>
                    </div>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Service
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
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="search-box position-relative">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" id="serviceSearch" class="form-control ps-5" placeholder="Search services...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" class="ps-4">#</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr>
                                    <td class="ps-4">{{ $service->service_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="service-icon-sm me-3">
                                                <i class="bi bi-gem text-dark"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $service->service_name }}</h6>
                                                <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $service->duration_minutes }} mins</td>
                                    <td>RM {{ number_format($service->price, 2) }}</td>
                                    <td>
                                        <span class="status-badge {{ $service->active ? 'active' : 'inactive' }}">
                                            {{ $service->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2 pe-4">
                                            <a href="{{ route('admin.services.show', $service->service_id) }}" 
                                               class="btn btn-action btn-light" 
                                               data-bs-toggle="tooltip" 
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.services.edit', $service->service_id) }}" 
                                               class="btn btn-action btn-light" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit Service">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-action btn-light text-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $service->service_id }}"
                                                    title="Delete Service">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            {{-- Include Delete Modal Here --}}
                                            @include('admin.services.delete', ['service' => $service])
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No services found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Add Pagination Links --}}
                @if($services->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{-- This line correctly renders Bootstrap 5 pagination links --}}
                        {{ $services->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('serviceSearch').addEventListener('input', filterServices);
    
    function filterServices() {
        const searchTerm = document.getElementById('serviceSearch').value.toLowerCase();
        
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const serviceName = row.querySelector('h6').textContent.toLowerCase();
            const description = row.querySelector('small').textContent.toLowerCase();
            
            const nameMatch = serviceName.includes(searchTerm);
            const descMatch = description.includes(searchTerm);
            
            if (nameMatch || descMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Confirm delete
    document.querySelectorAll('.service-delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection