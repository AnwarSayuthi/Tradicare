@extends('layout')

@section('css')
<style>
    .hero-section {
        background: linear-gradient(rgba(73, 54, 40, 0.8), rgba(73, 54, 40, 0.7)), url('/images/spa-bg.jpg');
        background-size: cover;
        background-position: center;
        min-height: 90vh;
        display: flex;
        align-items: center;
        color: #fff;
        position: relative;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 150px;
        background: linear-gradient(to top, #FAFAFA, transparent);
    }

    .service-card {
        background: #fff;
        border: none;
        border-radius: 15px;
        transition: all 0.4s ease;
        box-shadow: 0 5px 15px rgba(73, 54, 40, 0.1);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .service-card:hover::before {
        transform: scaleX(1);
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(73, 54, 40, 0.15);
    }

    .product-card {
        background: #fff;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .product-card img {
        height: 220px;
        object-fit: cover;
        transition: all 0.5s ease;
    }

    .product-card:hover img {
        transform: scale(1.05);
    }

    .section-title {
        color: #493628;
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
        text-align: center;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: #D6C0B3;
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 10px 20px rgba(73, 54, 40, 0.2);
    }

    @media (max-width: 767.98px) {
        .hero-section {
            min-height: 70vh;
            text-align: center;
        }
        
        .product-card img {
            height: 180px;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center hero-content">
        <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Experience Luxury Spa Treatment</h1>
        <p class="lead mb-5 animate__animated animate__fadeInUp">Indulge in our premium spa services and products for ultimate relaxation and rejuvenation</p>
        
        <div class="animate__animated animate__fadeInUp animate__delay-1s">
            <x-ui.button href="{{ route('customer.appointment.create') }}" class="btn-lg">
                <i class="bi bi-calendar-plus me-2"></i> Book Appointment
            </x-ui.button>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-6">
                <div class="text-center fade-in-up">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-gem"></i>
                    </div>
                    <h3 class="h5 mb-3">Premium Experience</h3>
                    <p class="text-muted">Enjoy our luxurious treatments with premium products</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="text-center fade-in-up" style="animation-delay: 0.2s">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3 class="h5 mb-3">Expert Therapists</h3>
                    <p class="text-muted">Our certified professionals provide exceptional care</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="text-center fade-in-up" style="animation-delay: 0.4s">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h3 class="h5 mb-3">Holistic Wellness</h3>
                    <p class="text-muted">Treatments designed for your body, mind and spirit</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="section-padding bg-light">
    <div class="container-lg container-fluid">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle mx-auto">Discover our range of premium treatments designed to relax, rejuvenate and restore</p>
        </div>
        <div class="row g-4">
            @foreach($services as $service)
            <div class="col-lg-4 col-md-6">
                <div class="service-card p-4 text-center h-100">
                    <div class="p-3">
                        <img src="{{ $service->icon ?? asset('images/service-icon.png') }}" alt="{{ $service->service_name }}" class="mb-4" width="64">
                        <h3 class="h5 mb-3" style="color: var(--primary);">{{ $service->service_name }}</h3>
                        <p class="text-muted mb-3">{{ $service->description }}</p>
                        <p class="fw-bold mb-4">${{ $service->price }}</p>
                        <a href="{{ route('customer.appointment.create') }}" class="btn btn-sm btn-primary-custom">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="section-padding">
    <div class="container-lg container-fluid">
        <div class="text-center mb-5">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle mx-auto">Enhance your spa experience with our premium products</p>
        </div>
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="product-card h-100">
                    <div class="overflow-hidden">
                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="w-100">
                    </div>
                    <div class="p-3">
                        <h4 class="h6 mb-2">{{ $product->product_name }}</h4>
                        <p class="fw-bold mb-3" style="color: var(--primary);">${{ $product->price }}</p>
                        <form action="{{ route('customer.cart.add', $product->product_id) }}" method="POST">
                            @csrf
                            <x-ui.button type="submit" class="w-100">
                                <i class="bi bi-cart-plus me-2"></i> Add to Cart
                            </x-ui.button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary">
                View All Products <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="section-padding bg-light">
    <div class="container-lg container-fluid">
        <div class="text-center mb-5">
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle mx-auto">Hear from our satisfied customers about their experience</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-quote fs-1" style="color: var(--primary-light);"></i>
                    </div>
                    <p class="text-center fs-5 mb-4">
                        "The massage therapy at Tradicare was absolutely amazing. The ambiance, the service, and the attention to detail made it a truly luxurious experience. I left feeling completely rejuvenated!"
                    </p>
                    <div class="text-center">
                        <h5 class="mb-1">Sarah Johnson</h5>
                        <p class="text-muted">Regular Client</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection