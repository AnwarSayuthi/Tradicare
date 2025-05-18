@extends('layout')

@section('title', 'Traditional Healing Services - Tradicare')

@section('content')
<div class="services-hero py-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">Traditional Healing Therapies</h1>
                <p class="lead">Ancient wisdom meets modern wellness for holistic healing and rejuvenation</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @foreach($services as $service)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="service-card h-100 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <div class="service-icon">
                    <i class="bi bi-gem"></i>
                </div>
                <div class="service-content">
                    <h3 class="service-title">{{ $service->service_name }}</h3>
                    <div class="service-meta">
                        <span><i class="bi bi-clock"></i> {{ $service->duration_minutes }} mins</span>
                        <span><i class="bi bi-tag"></i> RM{{ number_format($service->price, 2) }}</span>
                    </div>
                    <p class="service-description">{{ $service->description }}</p>
                    <a href="{{ route('customer.appointment.create', ['service_id' => $service->service_id]) }}" class="btn btn-primary-custom">
                        <i class="bi bi-gem me-2"></i>Book Now
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Benefits Section -->
    <div class="row mt-5 pt-5 border-top">
        <div class="col-12 text-center mb-4">
            <h2 class="section-title">Benefits of Traditional Healing</h2>
            <p class="section-subtitle mx-auto">Experience the time-tested advantages of our traditional treatments</p>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card text-center">
                <div class="benefit-icon">
                    <i class="bi bi-heart"></i>
                </div>
                <h4>Natural Healing</h4>
                <p>Harness the body's natural ability to heal itself through traditional techniques</p>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card text-center">
                <div class="benefit-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h4>Holistic Approach</h4>
                <p>Treatments that consider the whole person - body, mind, and spirit</p>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card text-center">
                <div class="benefit-icon">
                    <i class="bi bi-droplet"></i>
                </div>
                <h4>Reduced Pain</h4>
                <p>Alleviate chronic pain and discomfort through specialized techniques</p>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card text-center">
                <div class="benefit-icon">
                    <i class="bi bi-battery-charging"></i>
                </div>
                <h4>Increased Energy</h4>
                <p>Restore balance and boost natural energy levels for improved vitality</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .services-hero {
        background: linear-gradient(rgba(73, 54, 40, 0.8), rgba(73, 54, 40, 0.7)), url('/images/spa-bg.jpg');
        background-size: cover;
        background-position: center;
        color: #fff;
    }
    
    .lead-sm {
        font-size: 1.1rem;
        color: #666;
    }
    
    .service-filter {
        gap: 10px;
        margin-bottom: 2rem;
    }
    
    .service-filter .nav-link {
        color: var(--primary);
        border-radius: 30px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .service-filter .nav-link:hover {
        border-color: var(--primary-light);
    }
    
    .service-filter .nav-link.active {
        background-color: var(--primary);
        color: white;
    }
    
    .service-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        padding-top: 40px;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
    
    .service-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: -40px auto 20px;
        position: relative;
        z-index: 1;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .service-icon i {
        font-size: 2rem;
        color: var(--primary);
    }
    
    .service-content {
        padding: 0 25px 25px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .service-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--primary);
    }
    
    .service-meta {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        color: var(--secondary);
    }
    
    .service-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .service-description {
        color: #666;
        margin-bottom: 20px;
        flex-grow: 1;
    }
    
    .btn-primary-custom {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        background: var(--primary);
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }
    
    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(73, 54, 40, 0.2);
        color: #674c39;
    }
    
    /* Benefits Section */
    .benefit-card {
        padding: 2rem 1.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .benefit-icon {
        width: 80px;
        height: 80px;
        background: #ddb99c; /* Updated to beige color as shown in the image */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .benefit-icon i {
        font-size: 1.75rem;
        color: #674c39; /* Updated to dark brown color for better visibility */
    }
    
    .benefit-card h4 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary);
    }
    
    .benefit-card p {
        color: #666;
        font-size: 0.95rem;
    }
    
    /* Animation */
    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .service-card {
            margin-bottom: 60px;
        }
    }
    
    @media (max-width: 768px) {
        .service-filter {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 10px;
            justify-content: flex-start;
        }
        
        .service-filter .nav-link {
            white-space: nowrap;
            padding: 6px 15px;
            font-size: 0.9rem;
        }
        
        .service-meta {
            flex-direction: column;
            gap: 5px;
        }
    }
    
    @media (max-width: 576px) {
        .services-hero {
            text-align: center;
        }
        
        .services-hero h1 {
            font-size: 2rem;
        }
    }
</style>
@endsection