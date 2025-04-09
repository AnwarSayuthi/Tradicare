@extends('customer.nav')

@section('title', 'Our Products')

@section('content')
<div class="container mt-5">
    <!-- Page Title -->
    <div class="text-center">
        <h1 class="fw-bold">Our Products</h1>
    </div>

    <!-- Search Bar -->
    <div class="d-flex justify-content-center my-4">
        <input type="text" class="form-control w-50" placeholder="Find product you like">
    </div>

    <!-- Filter and Sort -->
    <div class="d-flex justify-content-between align-items-center">
        <div class="btn-group">
            <button type="button" class="btn btn-dark">All</button>
            <button type="button" class="btn btn-outline-dark">Type 1</button>
            <button type="button" class="btn btn-outline-dark">Type 2</button>
            <button type="button" class="btn btn-outline-dark">Type 3</button>
            <button type="button" class="btn btn-outline-dark">Type 4</button>
        </div>
        <div>
            <select class="form-select" style="width: 200px;">
                <option selected>Sort by price</option>
                <option value="1">Low to High</option>
                <option value="2">High to Low</option>
            </select>
        </div>
    </div>

    <!-- Product List -->
    <div class="row mt-4">
        @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="{{ asset('images/' . $product['image']) }}" class="card-img-top" alt="{{ $product['name'] }}">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">Short description of the product.</p>
                    <p class="card-text">RM{{ number_format($product['price'], 2) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
