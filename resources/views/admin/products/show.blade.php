@extends('admin_layout')

@section('title', 'Product Details - Tradicare Admin')

@section('page-title', 'Product Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Product Statistics - Added to match the reference image style -->
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

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">{{ $product->product_name }}</h4>
                        <p class="text-muted mb-0">Added on {{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.edit', $product->product_id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Product
                        </a>
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-4" style="min-height: 300px;">
                            @if($product->product_image)
                                <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid rounded-3 product-image" style="max-height: 300px; object-fit: contain;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                    <p class="mb-0 mt-2">No image available</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4 status-container">
                                    <span class="badge rounded-pill px-3 py-2 {{ $product->active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        <i class="bi {{ $product->active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $product->active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        <i class="bi bi-upc me-1"></i> ID: {{ $product->product_id }}
                                    </span>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="info-card p-3 rounded-3 h-100">
                                            <h6 class="text-muted mb-1">Regular Price</h6>
                                            <h5 class="fw-bold">RM{{ number_format($product->price, 2) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card p-3 rounded-3 h-100">
                                            <h6 class="text-muted mb-1">Sale Price</h6>
                                            <h5 class="fw-bold">
                                                @if($product->sale_price)
                                                    <span class="text-success">RM{{ number_format($product->sale_price, 2) }}</span>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="info-card p-3 rounded-3 h-100">
                                            <h6 class="text-muted mb-1">Category</h6>
                                            <h5 class="fw-bold">{{ $product->category }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card p-3 rounded-3 h-100">
                                            <h6 class="text-muted mb-1">Stock Quantity</h6>
                                            <h5 class="fw-bold">
                                                @if($product->stock_quantity > 10)
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        {{ $product->stock_quantity }} in stock
                                                    </span>
                                                @elseif($product->stock_quantity > 0)
                                                    <span class="text-warning">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                        {{ $product->stock_quantity }} in stock
                                                    </span>
                                                @else
                                                    <span class="text-danger">
                                                        <i class="bi bi-x-circle-fill me-1"></i>
                                                        Out of stock
                                                    </span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Description</h6>
                                    <div class="p-3 bg-light rounded-3 description-box">
                                        {{ $product->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white p-4 border-0">
                    <div class="d-flex justify-content-between action-buttons">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Products
                        </a>
                        <div>
                            <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="bi bi-toggle-on me-1"></i> Change Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-toggle-on me-2 text-primary"></i>Update Product Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.update-status', $product->product_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <p class="text-muted">Change status for: <strong>{{ $product->product_name }}</strong></p>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-medium">Status</label>
                        <select class="form-select form-select-lg" id="status" name="active">
                            <option value="1" {{ $product->active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$product->active ? 'selected' : '' }}>Inactive</option>
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

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger bg-danger-subtle border-0">
                    <p class="mb-0">Are you sure you want to delete this product? This action cannot be undone.</p>
                </div>
                <p class="mb-0"><strong>Product:</strong> {{ $product->product_name }}</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.products.destroy', $product->product_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Enhanced styling */
    .bg-success-subtle {
        background-color: rgba(46, 204, 113, 0.15);
    }
    
    .bg-danger-subtle {
        background-color: rgba(231, 76, 60, 0.15);
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .badge:hover {
        transform: translateY(-2px);
    }
    
    .btn {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        padding: 0.5rem 1.25rem;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.4);
    }
    
    .btn-primary:hover {
        box-shadow: 0 0.5rem 1rem rgba(var(--bs-primary-rgb), 0.2);
    }
    
    .card {
        transition: all 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }
    
    .info-card {
        background-color: #f8f9fa;
        border-left: 4px solid var(--bs-primary);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .description-box {
        max-height: 200px;
        overflow-y: auto;
        border-left: 4px solid var(--bs-primary);
    }
    
    .product-image {
        transition: all 0.3s ease;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    .modal-content {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .modal-header {
        background-color: #f8f9fa;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-header .d-flex {
            margin-top: 1rem;
            width: 100%;
        }
        
        .card-header .btn {
            flex: 1;
        }
        
        .status-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .status-container .badge {
            width: 100%;
            display: flex;
            align-items: center;
        }
    }
    
    @media (max-width: 767.98px) {
        .action-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .action-buttons a, 
        .action-buttons div {
            width: 100%;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-right: 0 !important;
        }
        
        .row.g-0 {
            flex-direction: column;
        }
        
        .col-md-4 {
            max-height: 250px;
        }
    }
    
    @media (max-width: 575.98px) {
        .card-body .p-4 {
            padding: 1rem !important;
        }
        
        .info-card {
            padding: 0.75rem !important;
        }
        
        h4.fw-bold {
            font-size: 1.25rem;
        }
        
        h5.fw-bold {
            font-size: 1rem;
        }
    }
</style>
@endsection