<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductListController extends Controller
{
    public function index()
    {
        // Example data to simulate a product list (replace this with actual database query in production)
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'category' => 'Category A', 'price' => 'RM100', 'description' => 'Description 1', 'inventory' => '15 in stock for 2 variants'],
            ['id' => 2, 'name' => 'Product 2', 'category' => 'Category B', 'price' => 'RM200', 'description' => 'Description 2', 'inventory' => '8 in stock for 2 variants'],
            ['id' => 3, 'name' => 'Product 3', 'category' => 'Category A', 'price' => 'RM150', 'description' => 'Description 3', 'inventory' => '25 in stock for 2 variants'],
            ['id' => 4, 'name' => 'Product 4', 'category' => 'Category C', 'price' => 'RM300', 'description' => 'Description 4', 'inventory' => 'Out of stock'],
        ];

        return view('admin.productList', compact('products'));
    }
}
