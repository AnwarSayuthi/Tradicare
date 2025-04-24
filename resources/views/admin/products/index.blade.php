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
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
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
            <ul class="nav nav-tabs nav-fill px-3 pt-3 border-0">
                <li class="nav-item">
                    <a class="nav-link rounded-3 {{ request('status') == '' ? 'active bg-light' : '' }}" href="{{ route('admin.products.index') }}">
                        All Products <span class="badge bg-secondary ms-1 rounded-pill">{{ $totalProducts }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-3 {{ request('status') == 'active' ? 'active bg-light' : '' }}" href="{{ route('admin.products.index', ['status' => 'active']) }}">
                        Active <span class="badge bg-success ms-1 rounded-pill">{{ $activeProducts }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-3 {{ request('status') == 'inactive' ? 'active bg-light' : '' }}" href="{{ route('admin.products.index', ['status' => 'inactive']) }}">
                        Inactive <span class="badge bg-danger ms-1 rounded-pill">{{ $inactiveProducts }}</span>
                    </a>
                </li>
            </ul>
            
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle border-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="40" class="rounded-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th width="80">IMAGE</th>
                            <th>PRODUCT</th>
                            <th>CATEGORY</th>
                            <th>PRICE</th>
                            <th>INVENTORY</th>
                            <th>STATUS</th>
                            <th width="100" class="rounded-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                                        <h5 class="fw-medium">No products found</h5>
                                        <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->product_id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-image">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="img-thumbnail rounded-3" width="60" height="60" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 60
                                                    <i class="bi bi-box text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product->product_id) }}" class="text-decoration-none text-dark fw-medium">
                                            {{ $product->product_name }}
                                        </a>
                                        <div class="text-muted small">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ ucfirst($product->category) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium">RM{{ number_format($product->price, 2) }}</div>
                                        @if($product->sale_price)
                                            <div class="text-success small">Sale: RM{{ number_format($product->sale_price, 2) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->stock_quantity > 10)
                                            <span class="text-success">{{ $product->stock_quantity }} in stock</span>
                                        @elseif($product->stock_quantity > 0)
                                            <span class="text-warning">{{ $product->stock_quantity }} in stock</span>
                                        @else
                                            <span class="text-danger">Out of stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ 
                                            $product->status === 'active' ? 'bg-success-subtle text-success' : 
                                            ($product->status === 'inactive' ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary') 
                                        }} px-3 py-2">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.products.show', $product->product_id) }}">
                                                        <i class="bi bi-eye me-2"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.products.edit', $product->product_id) }}">
                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $product->product_id }}">
                                                        <i class="bi bi-toggle-on me-2"></i> Change Status
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->product_id }}">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Update Status Modal -->
                                        <div class="modal fade" id="updateStatusModal{{ $product->product_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Product Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.products.update-status', $product->product_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <p>Change status for: <strong>{{ $product->product_name }}</strong></p>
                                                            <div class="mb-3">
                                                                <label for="status{{ $product->product_id }}" class="form-label">Status</label>
                                                                <select class="form-select" id="status{{ $product->product_id }}" name="status">
                                                                    <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                                    <!-- Removed draft option -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Product Modal -->
                                        <div class="modal fade" id="deleteProductModal{{ $product->product_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Product</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                                                        <p><strong>Product:</strong> {{ $product->product_name }}</p>
                                                        <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.products.destroy', $product->product_id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete Product</button>
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
                    <p class="text-muted mb-0">Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</p>
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
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
    
    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.15);
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