@extends('admin_layout')

@section('title', 'Customer Management - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    
    
    <!-- Customer Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3">
                        <i class="bi bi-people fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">All Customers</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3">
                        <i class="bi bi-person-check fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Active Customers</h6>
                        <h3 class="mb-0">{{ $activeCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3">
                        <i class="bi bi-person-dash fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Inactive Customers</h6>
                        <h3 class="mb-0">{{ $inactiveCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Customers</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Name, email or phone number" value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Customers Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="40">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th width="60">IMAGE</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>CONTACT NO.</th>
                            <th>STATUS</th>
                            <th>DATE CREATED</th>
                            <th width="100">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($customers->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-4">No customers found</td>
                            </tr>
                        @else
                            @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input customer-checkbox" type="checkbox" value="{{ $customer->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-circle">
                                            @if($customer->profile_image)
                                                <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="{{ $customer->name }}" class="rounded-circle" width="40" height="40">
                                            @else
                                                <div class="avatar-placeholder rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-decoration-none text-dark fw-medium">
                                            {{ $customer->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-secondary">{{ $customer->email }}</span>
                                    </td>
                                    <td>
                                        @if($customer->phone)
                                            +{{ $customer->phone }}
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $customer->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                                            {{ $customer->status === 'active' ? 'active' : 'inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                            Details
                                        </a>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.customers.show', $customer->id) }}">
                                                        <i class="bi bi-eye me-2"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editCustomerModal{{ $customer->id }}">
                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item {{ $customer->status === 'active' ? 'text-danger' : 'text-success' }}" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#updateStatusModal{{ $customer->id }}">
                                                        <i class="bi {{ $customer->status === 'active' ? 'bi-person-x' : 'bi-person-check' }} me-2"></i> 
                                                        {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Update Status Modal -->
                                        <div class="modal fade" id="updateStatusModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }} Customers
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to {{ $customer->status === 'active' ? 'deactivate' : 'activate' }} this customers?</p>
                                                        <p><strong>Name:</strong> {{ $customer->name }}</p>
                                                        <p><strong>Email:</strong> {{ $customer->email }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.customers.update-status', $customer->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="{{ $customer->status === 'active' ? 'inactive' : 'active' }}">
                                                            <button type="submit" class="btn {{ $customer->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                                                {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="text-muted mb-0">Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} Customers</p>
                </div>
                <div>
                    {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Custom badge styling */
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    .bg-success-subtle {
        background-color: rgba(46, 204, 113, 0.15);
    }
    
    .bg-danger-subtle {
        background-color: rgba(231, 76, 60, 0.15);
    }
    
    /* Table styling */
    .table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6c757d;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Button styling */
    .btn-icon {
        padding: 0.25rem 0.5rem;
        background: transparent;
        border: none;
    }
    
    .btn-icon:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 0.25rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .table th, .table td {
            padding: 0.75rem 0.5rem;
        }
        
        .badge {
            padding: 0.4rem 0.6rem;
            font-size: 0.7rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem;
        }
        
        h3 {
            font-size: 1.5rem;
        }
        
        .table th, .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.875rem;
        }
        
        .table th:nth-child(4),
        .table td:nth-child(4),
        .table th:nth-child(7),
        .table td:nth-child(7) {
            display: none;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const customerCheckboxes = document.querySelectorAll('.customer-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                customerCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
            
            customerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(customerCheckboxes).every(c => c.checked);
                    const someChecked = Array.from(customerCheckboxes).some(c => c.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                });
            });
        }
    });
</script>
@endsection