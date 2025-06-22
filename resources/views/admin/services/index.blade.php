@extends('admin_layout')

@section('title', 'Manage Services - Tradicare Admin')

@section('page-title', 'Services')

@section('content')
<style>
/* Enhanced CSS for better appearance - Following products page styling */
.service-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.service-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.service-table thead th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-transform: uppercase;
    font-size: 0.8rem;
}

.service-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.service-table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Enhanced stats cards layout - 3 cards optimized */
.stats-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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

/* Enhanced search section */
.search-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    padding: 2rem 2.5rem;
}

.search-box {
    position: relative;
}

.search-box input {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 1rem 1rem 1rem 3rem;
    transition: all 0.3s ease;
    background: white;
    font-size: 0.95rem;
    height: 58px;
}

.search-box input:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
}

.search-box i {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1rem;
    z-index: 10;
}

/* Enhanced service-specific styling */
.service-icon-sm {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.service-icon-sm:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.service-icon-sm i {
    color: #1976d2;
    font-size: 1.25rem;
}

.service-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0;
    font-size: 0.95rem;
    line-height: 1.3;
}

.service-description {
    color: #718096;
    font-size: 0.8rem;
    margin-bottom: 0;
    font-weight: 500;
}

.price-text {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
    background: linear-gradient(135deg, #00b894, #00a085);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.duration-text {
    color: #718096;
    font-size: 0.8rem;
    background: linear-gradient(135deg, #f1f3f4, #e9ecef);
    padding: 6px 12px;
    border-radius: 18px;
    display: inline-block;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.duration-text:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.status-badge {
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.status-badge.active {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    border-color: #00b894;
}

.status-badge.active:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 184, 148, 0.4);
}

.status-badge.inactive {
    background: linear-gradient(135deg, #fd79a8, #e84393);
    color: white;
    border-color: #fd79a8;
}

.status-badge.inactive:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(253, 121, 168, 0.4);
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    background: white;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Enhanced main card styling */
.main-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    background: white;
}

.main-card .card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 16px 16px 0 0 !important;
    border-bottom: 1px solid #e9ecef;
    padding: 2rem 2.5rem;
}

.main-card .card-header h4 {
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.main-card .card-header p {
    color: #718096;
    font-size: 1rem;
    margin-bottom: 0;
}

.main-card .card-body {
    padding: 2.5rem;
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

/* Color coding for 3 stats cards */
.stats-card:nth-child(1) .icon-container {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
}

.stats-card:nth-child(1) .icon-container i {
    color: #1976d2;
}

.stats-card:nth-child(2) .icon-container {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
}

.stats-card:nth-child(2) .icon-container i {
    color: #388e3c;
}

.stats-card:nth-child(3) .icon-container {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
}

.stats-card:nth-child(3) .icon-container i {
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

/* Alert styling */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin: 1.5rem 2.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Responsive design optimizations */
@media (max-width: 1200px) {
    .stats-container {
        grid-template-columns: repeat(3, 1fr);
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
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 0;
    }
    
    .stats-card:nth-child(3) {
        grid-column: 1 / 3;
        justify-self: center;
        max-width: 400px;
    }
    
    .search-section {
        padding: 1.75rem 2rem;
    }
    
    .main-card .card-header {
        padding: 1.75rem 2rem;
    }
    
    .main-card .card-body {
        padding: 2rem;
    }
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stats-card:nth-child(3) {
        grid-column: 1;
        justify-self: stretch;
        max-width: none;
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
    
    .search-section {
        padding: 1.5rem;
    }
    
    .main-card .card-header {
        padding: 1.5rem;
    }
    
    .main-card .card-body {
        padding: 1.5rem;
    }
    
    .service-table thead th:nth-child(3),
    .service-table tbody td:nth-child(3) {
        display: none;
    }
}

@media (max-width: 576px) {
    .stats-container {
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    
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
    
    .search-section {
        padding: 1.25rem;
    }
    
    .main-card .card-header {
        padding: 1.25rem;
    }
    
    .main-card .card-body {
        padding: 1.25rem;
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
    padding: 1.5rem 2.5rem;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Service Management</h1>
        
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Service
            </a>
        </div>
    </div>
    
    <!-- Service Statistics -->
    <div class="stats-container">
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-gem fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">All Services</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalServices }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Active Services</h6>
                        <h2 class="mb-0 fw-bold">{{ $activeServices }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-x-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Inactive Services</h6>
                        <h2 class="mb-0 fw-bold">{{ $inactiveServices }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove the Enhanced Search Section since we moved it to the top -->
    <!-- Main Services Card -->
    <div class="card main-card border-0">
        <div class="card-header bg-white border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="search-box position-relative" style="min-width: 300px; width: 50%;">
                <i class="bi bi-search"></i>
                <input type="text" id="serviceSearch" class="form-control" placeholder="Search services by name or description..." style="padding-left: 2.5rem; font-size: 0.95rem;">
            </div>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 service-table">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-4">ID</th>
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
                            <td class="ps-4">
                                <span class="fw-medium">{{ $service->service_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="service-icon-sm me-3">
                                        <i class="bi bi-gem"></i>
                                    </div>
                                    <div>
                                        <h6 class="service-name">{{ $service->service_name }}</h6>
                                        <small class="service-description">{{ Str::limit($service->description, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="duration-text">{{ $service->duration_minutes }} mins</span>
                            </td>
                            <td>
                                <span class="price-text">RM {{ number_format($service->price, 2) }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $service->active ? 'active' : 'inactive' }}">
                                    <i class="bi {{ $service->active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                    {{ $service->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.services.show', $service->service_id) }}" 
                                       class="btn btn-action" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $service->service_id) }}" 
                                       class="btn btn-action" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Service">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-action text-danger" 
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
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-gem"></i>
                                    <h5 class="fw-medium mb-1">No Services Found</h5>
                                    <p class="text-muted mb-3">There are no services matching your criteria.</p>
                                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i> Add New Service
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Add Pagination Links --}}
        @if($services->hasPages())
            <div class="card-footer bg-white border-0">
                {{-- This line correctly renders Bootstrap 5 pagination links --}}
                {{ $services->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
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