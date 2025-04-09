@extends('admin.nav')

@section('title', 'ProductList')

@section('content')
<div class="container mt-4">
    <h1 class="fw-bold">Products</h1>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <button class="btn btn-primary">+ Add Product</button>
        </div>
        <div>
            <input type="text" class="form-control" placeholder="Search..." />
        </div>
    </div>

    <table class="table mt-4 table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Description</th>
                <th>Inventory</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td><img src="{{ asset('images/product_placeholder.png') }}" alt="Product Image" style="width:50px;"></td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['category'] }}</td>
                <td>{{ $product['price'] }}</td>
                <td>{{ $product['description'] }}</td>
                <td>{{ $product['inventory'] }}</td>
                <td>
                    <button class="btn btn-secondary btn-sm">Details</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
