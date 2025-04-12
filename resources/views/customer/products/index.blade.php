@extends('layout')

@section('title', 'Shop Herbal Products - Tradicare')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="section-title">Premium Herbal Products</h1>
            <p class="section-subtitle">Discover our collection of high-quality herbal products for your wellness journey</p>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="category-filter mb-5">
        <div class="d-flex justify-content-center flex-wrap">
            <button class="filter-btn active mx-2 mb-2" data-filter="all">All Products</button>
            @foreach($categories as $category)
                <button class="filter-btn mx-2 mb-2" data-filter="{{ $category }}">{{ ucfirst($category) }}</button>
            @endforeach
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4 products-container">
        @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-category="{{ $product->category }}">
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid">
                        <!-- In the product-overlay div, update the form -->
                        <div class="product-overlay">
                            <form action="{{ route('customer.cart.add', $product->product_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-cart">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                            <a href="{{ route('customer.products.show', $product->product_id) }}" class="btn-view-details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->product_name }}</h3>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-category">{{ ucfirst($product->category) }}</span>
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                        <div class="stock-info {{ $product->stock_quantity > 10 ? 'in-stock' : ($product->stock_quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                            <i class="bi {{ $product->stock_quantity > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                            {{ $product->stock_quantity > 10 ? 'In Stock' : ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-bag-x display-1 text-muted"></i>
                    <h3 class="mt-4">No Products Available</h3>
                    <p class="text-muted">We're currently updating our inventory. Please check back soon.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('css')
<style>
    /* Product Card Styling */
    .product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(73, 54, 40, 0.1);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        height: 240px;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.08);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(73, 54, 40, 0.2);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        opacity: 0;
        transition: all 0.4s ease;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .btn-add-cart, .btn-view-details {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-add-cart:hover, .btn-view-details:hover {
        background: var(--primary);
        color: white;
    }

    .product-card:hover .btn-add-cart, 
    .product-card:hover .btn-view-details {
        transform: translateY(0);
        opacity: 1;
    }

    .product-card:hover .btn-view-details {
        transition-delay: 0.1s;
    }

    .product-info {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .product-category {
        font-size: 0.85rem;
        color: var(--secondary);
        font-weight: 500;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }

    .product-description {
        font-size: 0.9rem;
        color: #777;
        margin: 0.8rem 0;
        flex-grow: 1;
    }

    .stock-info {
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
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

    /* Category Filter Styling */
    .category-filter {
        padding: 1rem 0;
    }

    .filter-btn {
        background: transparent;
        border: 1px solid var(--primary-light);
        color: var(--primary);
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--gradient-primary);
        color: white;
        border-color: transparent;
    }

    /* Section Title Styling */
    .section-title {
        color: var(--primary);
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--primary-light);
    }

    .section-subtitle {
        color: var(--secondary);
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
    }

    /* Empty State Styling */
    .empty-state {
        padding: 3rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 1199.98px) {
        .product-image {
            height: 220px;
        }
    }

    @media (max-width: 991.98px) {
        .product-image {
            height: 200px;
        }
    }

    @media (max-width: 767.98px) {
        .section-title {
            font-size: 2rem;
        }
        
        .product-image {
            height: 220px;
        }
    }

    @media (max-width: 575.98px) {
        .filter-btn {
            padding: 0.4rem 1.2rem;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category filtering
        const filterButtons = document.querySelectorAll('.filter-btn');
        const productItems = document.querySelectorAll('.product-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                
                // Filter products
                productItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                        // Add animation
                        item.style.animation = 'fadeIn 0.5s forwards';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Add animation on page load
        productItems.forEach((item, index) => {
            item.style.animation = `fadeIn 0.5s forwards ${index * 0.1}s`;
        });
    });
    
    // Animation keyframes
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .product-item {
                opacity: 0;
            }
        </style>
    `);
</script>
@endsection