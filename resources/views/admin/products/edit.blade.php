@extends('admin_layout')

@section('title', 'Edit Product - Tradicare Admin')

@section('page-title', 'Edit Product')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-primary">Edit Product</h4>
                    <span class="badge bg-primary rounded-pill px-3 py-2">ID: {{ $product->product_id }}</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="card shadow-sm border-0 rounded-3 h-100">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4 fw-semibold border-bottom pb-3">Product Information</h5>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="product_name" class="form-label fw-medium">Product Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-lg @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                                                @error('product_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="price" class="form-label fw-medium">Regular Price (RM) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">RM</span>
                                                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                                </div>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label for="category" class="form-label fw-medium">Category <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-lg @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $product->category) }}" required>
                                                @error('category')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="stock_quantity" class="form-label fw-medium">Stock Quantity <span class="text-danger">*</span></label>
                                                <input type="number" min="0" class="form-control form-control-lg @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                                @error('stock_quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-medium">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" style="resize: none;">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card shadow-sm border-0 rounded-3">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4 fw-semibold border-bottom pb-3">Product Status</h5>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" role="switch" id="active-switch" 
                                                {{ $product->active ? 'checked' : '' }} onchange="updateActiveStatus(this)">
                                            <label class="form-check-label" for="active-switch">
                                                <span id="status-text" class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </label>
                                        </div>
                                        
                                        <p class="text-muted small mb-0">
                                            Toggle to make this product visible or hidden on the customer-facing website.
                                        </p>
                                        <div class="form-text text-muted mb-4">
                                            Active products will be visible to customers. Inactive products will be hidden from the store.
                                        </div>
                                        
                                        <!-- Product Image Section - Now in the same card as status -->
                                        <h5 class="card-title mt-4 mb-3 fw-semibold border-top border-bottom py-3">Product Image</h5>
                                        <div class="mb-3">
                                            <label for="image" class="form-label fw-medium small">Upload Image</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control @error('product_image') is-invalid @enderror" id="product_image" name="product_image" accept="image/*">
                                                <label class="input-group-text bg-light" for="image"><i class="bi bi-upload"></i></label>
                                            </div>
                                            <div class="form-text text-muted small mt-2">Recommended size: 800x800 pixels. Max file size: 2MB.</div>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="text-center mt-3">
                                            <div class="image-preview-container border rounded-3 p-3 d-flex align-items-center justify-content-center bg-light" style="height: 200px; transition: all 0.3s ease;">
                                                @if($product->product_image)
                                                    <img id="image-preview" src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid rounded-3" style="max-height: 180px; object-fit: contain;">
                                                    <div id="image-placeholder" class="text-center text-muted d-none">
                                                        <i class="bi bi-image fs-3"></i>
                                                        <p class="mb-0 mt-2 small">Image preview</p>
                                                    </div>
                                                @else
                                                    <img id="image-preview" src="#" alt="Product Image Preview" class="img-fluid d-none rounded-3" style="max-height: 180px; object-fit: contain;">
                                                    <div id="image-placeholder" class="text-center text-muted">
                                                        <i class="bi bi-image fs-3"></i>
                                                        <p class="mb-0 mt-2 small">Image preview</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('admin.products.show', $product->product_id) }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-eye me-1"></i> View Product
                            </a>
                            
                            <div>
                                <!-- Add a hidden input for the active status -->
                                <input type="hidden" name="active" value="{{ $product->active ? '1' : '0' }}" id="active-status-input">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePlaceholder = document.getElementById('image-placeholder');
        
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('d-none');
                        imagePlaceholder.classList.add('d-none');
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Active switch functionality
        const activeSwitch = document.getElementById('active-switch');
        const activeInput = document.getElementById('active-status-input');
        
        if (activeSwitch && activeInput) {
            activeSwitch.addEventListener('change', function() {
                activeInput.value = this.checked ? '1' : '0';
            });
        }
    });
</script>
@endsection

@section('css')
<style>
    /* Enhanced form styling */
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    .form-control-lg, .form-select-lg, .input-group-lg .form-control {
        border-radius: 0.5rem;
    }
    
    .input-group-text {
        border-radius: 0.5rem;
    }
    
    .input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: -1px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    
    .input-group:not(.has-validation) > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not(.form-floating),
    .input-group:not(.has-validation) > .dropdown-toggle:nth-last-child(n+3),
    .input-group:not(.has-validation) > .form-floating:not(:last-child) > .form-control,
    .input-group:not(.has-validation) > .form-floating:not(:last-child) > .form-select {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }
    
    /* Card styling */
    .card {
        transition: all 0.3s ease;
    }
    
    .card-body {
        transition: padding 0.3s ease;
    }
    
    /* Button styling */
    .btn {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
    
    .btn-primary {
        box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.4);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(var(--bs-primary-rgb), 0.2);
    }
    
    .btn-outline-secondary:hover, .btn-outline-primary:hover {
        transform: translateY(-2px);
    }
    
    /* Badge styling */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    /* Form check switch styling */
    .form-check-input {
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .card-body {
            padding: 1.25rem !important;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        
        .form-control-lg, .input-group-lg .form-control, .input-group-lg .input-group-text {
            font-size: 1rem;
            padding: 0.375rem 0.75rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem !important;
        }
        
        h4 {
            font-size: 1.4rem;
        }
        
        h5 {
            font-size: 1.15rem;
            
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .badge {
            margin-top: 0.5rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between > div {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        
        .btn {
            width: 100%;
            margin-right: 0 !important;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

<script>
    function updateStatusLabel(checkbox) {
        const statusText = document.getElementById('statusText');
        if (checkbox.checked) {
            statusText.textContent = 'Active';
            statusText.classList.remove('text-danger');
            statusText.classList.add('text-success');
        } else {
            statusText.textContent = 'Inactive';
            statusText.classList.remove('text-success');
            statusText.classList.add('text-danger');
        }
    }
</script>
