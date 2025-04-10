<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductListController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.productList', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string'
        ]);

        Product::create($validated);
        return redirect()->route('admin.productList')->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string'
        ]);

        $product->update($validated);
        return redirect()->route('admin.productList')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.productList')->with('success', 'Product deleted successfully');
    }
}
