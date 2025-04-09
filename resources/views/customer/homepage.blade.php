@extends('customer.nav')

@section('title', 'Home')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero rounded">
        <h1 class="fw-bold">Diversity, equity, and inclusion.</h1>
        <p class="lead">
            Tradicare aims to not only ensure its success but also attract a wider customer base, 
            ultimately contributing to its sustainability and growth in a competitive market.
        </p>
        <div class="d-flex justify-content-center mt-4">
            <p class="me-3"><strong>By:</strong> Khairul Anwar</p>
            <p><strong>Date issued:</strong> 12 June 2025</p>
        </div>
    </div>

    <!-- Discover Section -->
    <div class="discover-section mt-5">
        <h2>Discover</h2>
        <p class="text-muted">Explore our products and services to find the perfect fit for your needs.</p>
        <div class="row mt-4">
            <!-- Placeholder content -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Service 1</h5>
                        <p class="card-text">Short description of the service.</p>
                        <a href="#" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Service 2</h5>
                        <p class="card-text">Short description of the service.</p>
                        <a href="#" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Service 3</h5>
                        <p class="card-text">Short description of the service.</p>
                        <a href="#" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
