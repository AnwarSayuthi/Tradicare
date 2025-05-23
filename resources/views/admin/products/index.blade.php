@extends('admin_layout')

@section('title', 'Product Management - Tradicare Admin')

@section('page-title', 'Products')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Products</h1>
        
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.reports.generate', 'products') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Product
            </a>
        </div>
    </div>
    
    <!-- Product Statistics - Updated to match the reference image style -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-box-seam fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">All Products</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalProducts }}</h2>
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
                        <h6 class="text-muted mb-1">Active Products</h6>
                        <h2 class="mb-0 fw-bold">{{ $activeProducts }}</h2>
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
                        <h6 class="text-muted mb-1">Inactive Products</h6>
                        <h2 class="mb-0 fw-bold">{{ $inactiveProducts }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters section -->
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <label for="status" class="form-label fw-medium">Status</label>
                    <select name="status" id="status" class="form-select form-select-lg">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label for="category" class="form-label fw-medium">Category</label>
                    <select name="category" id="category" class="form-select form-select-lg">
                        <option value="">All Categories</option>
                        @foreach($categories as $category => $count)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }} ({{ $count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-12">
                    <label for="search" class="form-label fw-medium">Search</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Product name or description" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="60">#</th>
                            <th width="80">IMAGE</th>
                            <th>PRODUCT</th>
                            <th>CATEGORY</th>
                            <th>PRICE</th>
                            <th>INVENTORY</th>
                            <th>STATUS</th>
                            <th width="100">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                            <td>
                                @if($product->product_image)
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->product_name }}" class="img-thumbnail" width="50">
                                @else
                                <div class="no-image-placeholder">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 fs-6">{{ $product->product_name }}</h6>
                                </div>
                            </td>
                            <td><span class="small">{{ ucfirst($product->category) }}</span></td>
                            <td><span class="small">RM{{ number_format($product->price, 2) }}</span></td>
                            <td><span class="small">{{ $product->stock_quantity ?? '0' }} in stock</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <span class="badge rounded-pill px-2 py-1 small {{ $product->active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        <i class="bi {{ $product->active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $product->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.products.show', $product->product_id) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.products.edit', $product->product_id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.products.destroy', $product->product_id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-box fs-1 text-secondary mb-3"></i>
                                    <h5>No products found</h5>
                                    <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
    
    <!-- Remove the old pagination div -->
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
    
    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.15);
    }
    
    /* Table styling */
    .table th {
        text-align: center;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6c757d;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .product-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem;
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
    
    /* Nav tabs styling */
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1rem;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary);
        border-bottom: 2px solid var(--primary);
        background-color: transparent;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom: 2px solid #e9ecef;
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
        .table th:nth-child(6),
        .table td:nth-child(6) {
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
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
            
            productCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(productCheckboxes).every(c => c.checked);
                    const someChecked = Array.from(productCheckboxes).some(c => c.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                });
            });
        }
    });
</script>
@endsection