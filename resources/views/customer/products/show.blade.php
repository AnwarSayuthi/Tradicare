@extends('layout')

@section('title', $product->product_name . ' - Tradicare')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
        </ol>
    </nav>

    <div class="product-detail-container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-image-container">
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="product-main-image">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-info-container">
                    <span class="product-category">{{ ucfirst($product->category) }}</span>
                    <h1 class="product-title">{{ $product->product_name }}</h1>
                    
                    <div class="product-price-container mb-4">
                        <span class="product-price">${{ number_format($product->price, 2) }}</span>
                    </div>
                    
                    <div class="product-description mb-4">
                        <p>{{ $product->description }}</p>
                    </div>
                    
                    <div class="stock-info mb-4 {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                        <i class="bi {{ $product->stock_quantity > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                        {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                    </div>
                    
                    @if($product->stock_quantity > 0)
                    <!-- Update the form action in the product detail section -->
                    <form action="{{ route('customer.cart.add', $product->product_id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4 col-6">
                                <div class="quantity-selector">
                                    <button type="button" class="quantity-btn minus-btn">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="quantity-input">
                                    <button type="button" class="quantity-btn plus-btn">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8 col-12">
                                <button type="submit" class="btn btn-primary-custom w-100">
                                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">SKU:</span>
                            <span class="meta-value">{{ $product->sku ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Weight:</span>
                            <span class="meta-value">{{ $product->weight ?? 'N/A' }} {{ $product->weight_unit ?? 'g' }}</span>
                        </div>
                    </div>
                    
                    <div class="product-benefits mt-4">
                        <h4 class="benefits-title">Benefits</h4>
                        <ul class="benefits-list">
                            <li><i class="bi bi-check2-circle"></i> 100% Natural Ingredients</li>
                            <li><i class="bi bi-check2-circle"></i> Ethically Sourced</li>
                            <li><i class="bi bi-check2-circle"></i> No Artificial Additives</li>
                            <li><i class="bi bi-check2-circle"></i> Supports Holistic Wellness</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($relatedProducts->count() > 0)
    <div class="related-products mt-5 pt-5">
        <h2 class="section-title text-center mb-4">Related Products</h2>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6">
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $relatedProduct->product_image) }}" alt="{{ $relatedProduct->product_name }}" class="img-fluid">
                        <div class="product-overlay">
                            <form action="{{ route('customer.cart.add', $relatedProduct->product_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-add-cart">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                            <a href="{{ route('customer.products.show', $relatedProduct->product_id) }}" class="btn-view-details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $relatedProduct->product_name }}</h3>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-category">{{ ucfirst($relatedProduct->category) }}</span>
                            <span class="product-price">${{ number_format($relatedProduct->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('css')
<style>
    /* Product Detail Styling */
    .product-detail-container {
        margin-bottom: 4rem;
    }
    
    .product-image-container {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-main-image {
        width: 100%;
        height: auto;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    
    .product-main-image:hover {
        transform: scale(1.05);
    }
    
    .product-info-container {
        padding: 1rem;
    }
    
    .product-category {
        display: inline-block;
        font-size: 0.9rem;
        color: var(--secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        background: rgba(139, 115, 85, 0.1);
        padding: 0.3rem 1rem;
        border-radius: 20px;
    }
    
    .product-title {
        font-size: 2.2rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .product-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .product-description {
        font-size: 1rem;
        line-height: 1.7;
        color: #555;
    }
    
    .stock-info {
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 0;
    }
    
    .in-stock {
        color: #2ecc71;
    }
    
    .low-stock {
        color: #f39c12;
    }
    
    .out-of-stock {
        color: #e74c3c;
    }
    
    /* Quantity Selector */
    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid rgba(73, 54, 40, 0.2);
        border-radius: 8px;
        overflow: hidden;
        height: 100%;
    }
    
    .quantity-btn {
        background: rgba(73, 54, 40, 0.05);
        border: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .quantity-btn:hover {
        background: rgba(73, 54, 40, 0.1);
    }
    
    .quantity-input {
        width: 100%;
        border: none;
        text-align: center;
        font-weight: 500;
        padding: 0.5rem;
        -moz-appearance: textfield;
    }
    
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Product Meta */
    .product-meta {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(73, 54, 40, 0.1);
    }
    
    .meta-item {
        display: flex;
        margin-bottom: 0.5rem;
    }
    
    .meta-label {
        font-weight: 600;
        color: var(--primary);
        width: 100px;
    }
    
    .meta-value {
        color: #555;
    }
    
    /* Product Benefits */
    .benefits-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .benefits-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0.8rem;
        color: #555;
    }
    
    .benefits-list i {
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    
    .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover {
        color: var(--secondary);
    }
    
    .breadcrumb-item.active {
        color: var(--secondary);
    }
    
    .breadcrumb-item+.breadcrumb-item::before {
        color: var(--primary-light);
    }
    
    /* Related Products */
    .related-products {
        border-top: 1px solid rgba(73, 54, 40, 0.1);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .product-title {
            font-size: 1.8rem;
        }
        
        .product-price {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .product-image-container {
            height: 350px;
        }
        
        .product-main-image {
            height: 100%;
            object-fit: contain;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity selector functionality
        const minusBtn = document.querySelector('.minus-btn');
        const plusBtn = document.querySelector('.plus-btn');
        const quantityInput = document.querySelector('.quantity-input');
        
        if (minusBtn && plusBtn && quantityInput) {
            const maxQuantity = parseInt(quantityInput.getAttribute('max'));
            
            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue < maxQuantity) {
                    quantityInput.value = currentValue + 1;
                }
            });
            
            quantityInput.addEventListener('change', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue < 1) {
                    quantityInput.value = 1;
                } else if (currentValue > maxQuantity) {
                    quantityInput.value = maxQuantity;
                }
            });
        }
        
        // Image zoom effect on hover
        const productImage = document.querySelector('.product-main-image');
        const imageContainer = document.querySelector('.product-image-container');
        
        if (productImage && imageContainer && window.innerWidth > 992) {
            imageContainer.addEventListener('mousemove', function(e) {
                const { left, top, width, height } = this.getBoundingClientRect();
                const x = (e.clientX - left) / width;
                const y = (e.clientY - top) / height;
                
                productImage.style.transformOrigin = `${x * 100}% ${y * 100}%`;
                productImage.style.transform = 'scale(1.5)';
            });
            
            imageContainer.addEventListener('mouseleave', function() {
                productImage.style.transformOrigin = 'center center';
                productImage.style.transform = 'scale(1)';
            });
        }
    });
</script>
@endsection