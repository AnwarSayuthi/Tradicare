@extends('admin_layout')

@section('title', 'Add New Product - Tradicare Admin')

@section('page-title', 'Add New Product')

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
                <div class="card-header bg-white p-4 border-0">
                    <h4 class="mb-0 fw-bold text-primary">Add New Product</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="card shadow-sm border-0 rounded-3 h-100">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4 fw-semibold border-bottom pb-3">Product Information</h5>
                                        
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label fw-medium">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                            @error('product_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label for="price" class="form-label fw-medium">Regular Price (RM) <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text bg-light">RM</span>
                                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                                    @error('price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="sale_price" class="form-label fw-medium">Sale Price (RM)</label>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text bg-light">RM</span>
                                                    <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                                    @error('sale_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label for="category" class="form-label fw-medium">Category <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-lg @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" required>
                                                @error('category')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="stock_quantity" class="form-label fw-medium">Stock Quantity <span class="text-danger">*</span></label>
                                                <input type="number" min="0" class="form-control form-control-lg @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                                @error('stock_quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-medium">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" style="resize: none;">{{ old('description') }}</textarea>
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
                                        <div class="form-check form-switch mb-4">
                                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', '1') == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                            <label class="form-check-label fw-medium ms-2" for="active">Active</label>
                                        </div>
                                        <div class="form-text text-muted mb-4">
                                            Active products will be visible to customers. Inactive products will be hidden from the store.
                                        </div>
                                        
                                        <!-- Product Image Section - Now in the same card as status -->
                                        <h5 class="card-title mt-4 mb-3 fw-semibold border-top border-bottom py-3">Product Image</h5>
                                        <div class="mb-3">
                                            <label for="image" class="form-label fw-medium">Upload Image</label>
                                            <input type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" id="image" name="product_image" accept="image/*">
                                            <div class="form-text text-muted mt-2">Recommended size: 800x800 pixels. Max file size: 2MB.</div>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="text-center mt-4">
                                            <div class="image-preview-container border rounded-3 p-3 d-flex align-items-center justify-content-center bg-light" style="height: 220px;">
                                                <img id="image-preview" src="#" alt="Product Image Preview" class="img-fluid d-none rounded-3" style="max-height: 200px; object-fit: contain;">
                                                <div id="image-placeholder" class="text-center text-muted">
                                                    <i class="bi bi-image fs-1"></i>
                                                    <p class="mb-0 mt-2">Image preview</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg me-2">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-1"></i> Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                    imagePlaceholder.classList.add('d-none');
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.classList.add('d-none');
                imagePlaceholder.classList.remove('d-none');
            }
        });
        
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
@endsection