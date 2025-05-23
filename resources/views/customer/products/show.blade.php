@extends('layout')

@section('title', $product->product_name . ' - Tradicare')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.products.index', ['category' => $product->category]) }}">{{ ucfirst($product->category) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
        </ol>
    </nav>

    <div class="product-detail-container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-image-container">
                    <a href="{{ route('customer.products.index', ['category' => $product->category]) }}" class="back-button">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    @if($product->product_image)
                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->product_name }}" class="product-main-image rounded shadow-sm" style="object-fit:cover;aspect-ratio:1/1;">
                    @else
                        <img src="{{ asset('images/default-product.png') }}" alt="No image" class="product-main-image rounded shadow-sm" style="object-fit:cover;aspect-ratio:1/1;">
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-info-container">
                    <span class="product-category">{{ ucfirst($product->category) }}</span>
                    <h1 class="product-title">{{ $product->product_name }}</h1>
                    
                    <div class="product-price-container mb-4">
                        <h2 class="product-price mb-3">RM{{ number_format($product->price, 2) }}</h2>
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
        <h2 class="section-title text-center mb-5">Related Products</h2>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6">
                <div class="product-card">
                    <a href="{{ route('customer.products.show', $relatedProduct->product_id) }}" class="product-link">
                        <div class="product-image">
                            @if($relatedProduct->product_image)
                                <img src="{{ $relatedProduct->getImageUrl() }}" alt="{{ $relatedProduct->product_name }}" class="img-fluid">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $relatedProduct->product_name }}" class="img-fluid">
                            @endif
                        </div>
                        <div class="product-content">
                            <h3 class="product-title">{{ $relatedProduct->product_name }}</h3>
                            <div class="product-category-badge">{{ ucfirst($relatedProduct->category) }}</div>
                            <div class="product-price">RM{{ number_format($relatedProduct->price, 2) }}</div>
                            <p class="product-description">{{ Str::limit($relatedProduct->description, 60) }}</p>
                            <div class="stock-status {{ $relatedProduct->stock_quantity > 0 ? 'in-stock' : 'out-of-stock' }}">
                                <i class="bi {{ $relatedProduct->stock_quantity > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                {{ $relatedProduct->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                            </div>
                        </div>
                    </a>
                    <div class="product-actions">
                        <form action="{{ route('customer.cart.add', $relatedProduct->product_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-cart" title="Add to Cart">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </form>
                        <a href="{{ route('customer.products.show', $relatedProduct->product_id) }}" class="btn-view-details" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($relatedProducts->count() > 9)
            <div class="d-flex justify-content-center mt-4">
                {{ $relatedProducts->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
    @endif
</div>
@endsection

@section('css')
<style>
    /* Back Button */
    .back-button {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
        background-color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .back-button:hover {
        background-color: var(--primary);
        color: #fff;
        transform: translateY(-2px);
    }
    
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
    
    .no-image-placeholder {
        width: 100%;
        height: 100%;
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #adb5bd;
    }
    
    .no-image-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .no-image-placeholder p {
        font-size: 1rem;
        margin: 0;
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
    
    .section-title {
        position: relative;
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--primary);
        border-radius: 3px;
    }
    
    /* Product Card Styling */
    .product-card {
        position: relative;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .product-link {
        display: block;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }
    
    .product-image {
        height: 180px;
        overflow: hidden;
        position: relative;
        background: #f9f9f9;
        border-radius: 16px 16px 0 0;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-content {
        padding: 1.2rem;
        text-align: center;
        position: relative;
        background: #fff;
        border-radius: 0 0 16px 16px;
    }
    
    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        min-height: 2.8rem;
    }
    
    .product-category-badge {
        display: inline-block;
        font-size: 0.8rem;
        color: var(--secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        background: rgba(139, 115, 85, 0.1);
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
    }
    
    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    .product-description {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 0.8rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .stock-status {
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    
    .in-stock {
        color: #2ecc71;
    }
    
    .out-of-stock {
        color: #e74c3c;
    }
    
    .product-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10;
    }
    
    .btn-add-cart,
    .btn-view-details {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .btn-add-cart:hover,
    .btn-view-details:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-2px);
    }
    
    @media (max-width: 991.98px) {
        .product-title {
            font-size: 1rem;
            min-height: 2.5rem;
        }
        
        .product-price {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .product-image {
            height: 160px;
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