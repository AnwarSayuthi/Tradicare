<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Sample product data
        $products = [
            ['name' => 'Product 1', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 2', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 3', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 4', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 5', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 6', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 7', 'price' => 999.99, 'image' => 'placeholder.png'],
            ['name' => 'Product 8', 'price' => 999.99, 'image' => 'placeholder.png'],
        ];

        return view('customer.products', compact('products'));
    }
}
