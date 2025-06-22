@extends('admin_layout')

@section('title', 'Product Management - Tradicare Admin')

@section('page-title', 'Products')

@section('content')
<style>
/* Enhanced CSS for better appearance - Following orders page styling */
.product-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.product-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.product-table thead th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    text-transform: uppercase;
    font-size: 0.8rem;
}

.product-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.product-table tbody tr:hover {
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

/* Enhanced filter section - Full width optimized layout */
.filter-card {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.filter-card .card-body {
    padding: 2rem 2.5rem;
}

.filter-card .row {
    align-items: end;
    gap: 0;
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}

.filter-card .col-12,
.filter-card .col-md-6,
.filter-card .col-lg-3,
.filter-card .col-lg-4,
.filter-card .col-lg-2,
.filter-card .col-md-4,
.filter-card .col-md-8 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.filter-card .form-floating {
    margin-bottom: 0;
}

.filter-card .form-floating > .form-control,
.filter-card .form-floating > .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    background: white;
    height: 58px;
    font-size: 0.95rem;
    padding: 1rem 0.75rem 0.25rem 0.75rem;
}

.filter-card .form-floating > label {
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.filter-card .form-floating > .form-control:focus,
.filter-card .form-floating > .form-select:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
}

.filter-card .form-floating > .form-control:focus ~ label,
.filter-card .form-floating > .form-select:focus ~ label,
.filter-card .form-floating > .form-control:not(:placeholder-shown) ~ label,
.filter-card .form-floating > .form-select:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    color: #667eea;
}

.filter-card .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    height: 58px;
    font-size: 0.95rem;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    min-width: 130px;
}

.filter-card .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
}

.filter-card .btn-primary i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

/* Enhanced product-specific styling */
.product-image {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border-radius: 10px;
    border: 3px solid #f1f3f4;
    transition: all 0.3s ease;
}

.product-image:hover {
    transform: scale(1.1);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.no-image-placeholder {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #f1f3f4;
    transition: all 0.3s ease;
}

.no-image-placeholder:hover {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    border-color: #cbd5e0;
}

.product-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0;
    font-size: 0.95rem;
    line-height: 1.3;
}

.category-badge {
    background: linear-gradient(135deg, #a29bfe, #6c5ce7);
    color: white;
    padding: 6px 12px;
    border-radius: 18px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.category-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(162, 155, 254, 0.4);
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

.stock-text {
    color: #718096;
    font-size: 0.8rem;
    font-weight: 500;
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

.status-active {
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    border-color: #00b894;
}

.status-active:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 184, 148, 0.4);
}

.status-inactive {
    background: linear-gradient(135deg, #fd79a8, #e84393);
    color: white;
    border-color: #fd79a8;
}

.status-inactive:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(253, 121, 168, 0.4);
}

.btn-icon {
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

.btn-icon:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
    
    .filter-card .card-body {
        padding: 1.75rem 2rem;
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
    
    .filter-card .card-body {
        padding: 1.5rem;
    }
    
    .filter-card .row {
        row-gap: 1rem;
    }
}

@media (max-width: 576px) {
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
    
    .filter-card .card-body {
        padding: 1.25rem;
    }
    
    .product-table thead th:nth-child(4),
    .product-table tbody td:nth-child(4),
    .product-table thead th:nth-child(6),
    .product-table tbody td:nth-child(6) {
        display: none;
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
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 fw-bold text-primary">Product Management</h1>
        
        <div class="d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Product
            </a>
        </div>
    </div>
    
    <!-- Product Statistics -->
    <div class="stats-container">
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">All Products</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalProducts }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Active Products</h6>
                        <h2 class="mb-0 fw-bold">{{ $activeProducts }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card-wrapper">
            <div class="card stats-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-container me-3">
                        <i class="bi bi-x-circle fs-3 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Inactive Products</h6>
                        <h2 class="mb-0 fw-bold">{{ $inactiveProducts }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card filter-card shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-floating">
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <label for="status">Status</label>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-floating">
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category => $count)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }} ({{ $count }})
                                </option>
                            @endforeach
                        </select>
                        <label for="category">Category</label>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Product name or description" value="{{ request('search') }}">
                        <label for="search">Search by product name or description</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="card product-table border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-4">ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Product</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">Inventory</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium">{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</span>
                            </td>
                            <td>
                                @if($product->product_image)
                                    <img src="{{ $product->getImageUrl() }}" alt="{{ $product->product_name }}" class="product-image">
                                @else
                                    <div class="no-image-placeholder">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <h6 class="product-name">{{ $product->product_name }}</h6>
                            </td>
                            <td>
                                <span class="category-badge">{{ ucfirst($product->category) }}</span>
                            </td>
                            <td>
                                <span class="price-text">RM{{ number_format($product->price, 2) }}</span>
                            </td>
                            <td>
                                <span class="stock-text">{{ $product->stock_quantity ?? '0' }} in stock</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $product->active ? 'status-active' : 'status-inactive' }}">
                                    <i class="bi {{ $product->active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                    {{ $product->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.products.show', $product->product_id) }}">
                                                <i class="bi bi-eye me-2"></i>View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.products.edit', $product->product_id) }}">
                                                <i class="bi bi-pencil me-2"></i>Edit Product
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.products.destroy', $product->product_id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bi bi-trash me-2"></i>Delete Product
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-box fs-1 text-muted mb-3"></i>
                                    <h5 class="fw-medium mb-1">No Products Found</h5>
                                    <p class="text-muted mb-3">There are no products matching your criteria.</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
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
        
        @if($products->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection