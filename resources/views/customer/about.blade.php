@extends('layout')

@section('title', 'About Us - Tradicare')

@section('content')
<div class="about-hero py-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">Our Story</h1>
                <p class="lead">Preserving ancient healing wisdom for modern wellness</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="about-image-wrapper">
                <img src="{{ asset('images/about-us.jpg') }}" alt="Traditional healing practices" class="img-fluid rounded-lg shadow-lg">
                <div class="experience-badge">
                    <span class="years">25+</span>
                    <span class="text">Years of Experience</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <h2 class="section-title mb-4">Honoring Tradition, Embracing Innovation</h2>
            <p class="mb-4">Founded in 1998, Tradicare began as a small family practice dedicated to preserving the ancient healing arts of traditional medicine. Our founder, Master Healer Tan Sri Dr. Ahmad, spent decades studying with renowned practitioners across Southeast Asia, learning techniques passed down through generations.</p>
            <p class="mb-4">Today, Tradicare has grown into a premier wellness center that combines time-tested traditional healing methods with modern therapeutic approaches. Our mission remains unchanged: to provide authentic, effective healing treatments that address the root causes of pain and discomfort.</p>
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="about-feature">
                        <div class="about-feature-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <h5>Certified Practitioners</h5>
                        <p>Our healers undergo rigorous training and certification</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-feature">
                        <div class="about-feature-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h5>Holistic Approach</h5>
                        <p>We treat the whole person, not just symptoms</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row my-5 py-5 border-top border-bottom">
        <div class="col-12 text-center mb-5">
            <h2 class="section-title">Our Philosophy</h2>
            <p class="section-subtitle mx-auto">The principles that guide our healing practice</p>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="philosophy-card text-center h-100">
                <div class="philosophy-icon">
                    <i class="bi bi-balance-scale"></i>
                </div>
                <h4>Balance & Harmony</h4>
                <p>We believe that health comes from balance within the body's systems. Our treatments aim to restore harmony between body, mind, and spirit.</p>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="philosophy-card text-center h-100">
                <div class="philosophy-icon">
                    <i class="bi bi-flower1"></i>
                </div>
                <h4>Natural Healing</h4>
                <p>Our treatments harness the body's innate healing abilities, using natural remedies and techniques that have stood the test of time.</p>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="philosophy-card text-center h-100">
                <div class="philosophy-icon">
                    <i class="bi bi-person-check"></i>
                </div>
                <h4>Personalized Care</h4>
                <p>We recognize that each person is unique. Our treatments are tailored to your specific needs and constitution.</p>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="section-title">Meet Our Master Healers</h2>
            <p class="section-subtitle mx-auto">Skilled practitioners with decades of experience</p>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="team-card">
                <div class="team-image">
                    <img src="{{ asset('images/healer1.jpg') }}" alt="Master Healer Ahmad" class="img-fluid">
                </div>
                <div class="team-content">
                    <h4>Master Ahmad</h4>
                    <p class="team-role">Founder & Senior Healer</p>
                    <p class="team-bio">With over 40 years of experience, Master Ahmad specializes in bone and joint treatments using traditional techniques passed down through five generations.</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="team-card">
                <div class="team-image">
                    <img src="{{ asset('images/healer2.jpg') }}" alt="Dr. Sarah Lim" class="img-fluid">
                </div>
                <div class="team-content">
                    <h4>Dr. Sarah Lim</h4>
                    <p class="team-role">Head of Therapeutic Massage</p>
                    <p class="team-bio">Dr. Lim combines her medical background with traditional massage techniques to create effective treatments for chronic pain and mobility issues.</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="team-card">
                <div class="team-image">
                    <img src="{{ asset('images/healer3.jpg') }}" alt="Master Wong" class="img-fluid">
                </div>
                <div class="team-content">
                    <h4>Master Wong</h4>
                    <p class="team-role">Circulation & Nerve Specialist</p>
                    <p class="team-bio">Master Wong's unique approach to treating circulation and nerve-related conditions has helped thousands of patients regain comfort and mobility.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row testimonial-section py-5">
        <div class="col-12 text-center mb-5">
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle mx-auto">Real stories from people we've helped</p>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p>"After years of chronic back pain and trying countless treatments, Tradicare's Joint Pain Relief Therapy finally gave me the relief I've been searching for. I can now play with my grandchildren without pain!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        <img src="{{ asset('images/testimonial1.jpg') }}" alt="Client testimonial">
                    </div>
                    <div class="testimonial-info">
                        <h5>Madam Tan</h5>
                        <p>Client for 3 years</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p>"The Bone Fracture Recovery Treatment accelerated my healing after a sports injury. The doctors were amazed at how quickly I recovered. The practitioners at Tradicare truly understand the body's healing mechanisms."</p>
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        <img src="{{ asset('images/testimonial2.jpg') }}" alt="Client testimonial">
                    </div>
                    <div class="testimonial-info">
                        <h5>David Lee</h5>
                        <p>Professional athlete</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p>"I had severe circulation problems in my legs for years. After just a few sessions of Vein & Circulation Therapy, I noticed significant improvement. The swelling reduced and I can now walk comfortably again."</p>
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        <img src="{{ asset('images/testimonial3.jpg') }}" alt="Client testimonial">
                    </div>
                    <div class="testimonial-info">
                        <h5>Puan Aminah</h5>
                        <p>Client for 2 years</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .about-hero {
        background: linear-gradient(rgba(73, 54, 40, 0.8), rgba(73, 54, 40, 0.7)), url('/images/about-hero.jpg');
        background-size: cover;
        background-position: center;
        color: #fff;
        padding: 80px 0;
    }
    
    .about-image-wrapper {
        position: relative;
        padding: 20px;
    }
    
    .experience-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--gradient-primary);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(73, 54, 40, 0.2);
    }
    
    .experience-badge .years {
        display: block;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .experience-badge .text {
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .section-title {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .section-subtitle {
        color: #666;
        max-width: 700px;
        margin-bottom: 3rem;
    }
    
    .about-feature {
        padding: 20px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .about-feature:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .about-feature-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    
    .about-feature-icon i {
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    .philosophy-card {
        padding: 30px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .philosophy-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .philosophy-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .philosophy-icon i {
        font-size: 2rem;
        color: var(--primary);
    }
    
    .team-card {
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .team-image {
        height: 300px;
        overflow: hidden;
    }
    
    .team-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.5s ease;
    }
    
    .team-card:hover .team-image img {
        transform: scale(1.1);
    }
    
    .team-content {
        padding: 25px;
    }
    
    .team-role {
        color: var(--secondary);
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .testimonial-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .testimonial-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .testimonial-content {
        padding: 30px;
        position: relative;
    }
    
    .testimonial-content::before {
        content: '"';
        position: absolute;
        top: 10px;
        left: 20px;
        font-size: 5rem;
        color: var(--primary-light);
        opacity: 0.2;
        font-family: serif;
        line-height: 1;
    }
    
    .testimonial-rating {
        margin-bottom: 15px;
        color: #FFD700;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
        padding: 20px 30px;
        background: var(--primary-light);
    }
    
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 15px;
        border: 3px solid #fff;
    }
    
    .testimonial-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .testimonial-info h5 {
        margin: 0;
        color: var(--primary);
        font-weight: 600;
    }
    
    .testimonial-info p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--primary);
        opacity: 0.8;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .experience-badge {
            position: relative;
            display: inline-block;
            margin-top: 20px;
        }
    }
    
    @media (max-width: 767px) {
        .about-hero {
            padding: 60px 0;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
    }
</style>
@endsection