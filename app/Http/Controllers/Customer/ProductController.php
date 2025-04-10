<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Sort by price if requested
        if ($request->has('sort')) {
            if ($request->sort == 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        }
        
        // Search by name if provided
        if ($request->has('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->get();
        
        // Get unique categories for filter
        $categories = Product::distinct()->pluck('category');
        
        return view('customer.products', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('customer.products.show', compact('product'));
    }
}
