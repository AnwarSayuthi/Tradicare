<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Get common product statistics for all views
     * 
     * @return array
     */
    private function getProductStats()
    {
        return [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('active', true)->count(),
            'inactiveProducts' => Product::where('active', false)->count(),
        ];
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Apply filters
        if ($request->has('active') && $request->active != '') {
            $query->where('active', $request->active == 'true' ? true : false);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        // Get products with pagination
        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get product statistics
        $stats = $this->getProductStats();
        
        // Get unique categories for filter dropdown
        $categories = Product::distinct()->pluck('category')->filter()->toArray();
        
        return view('admin.products.index', array_merge(
            compact('products', 'categories'),
            $stats
        ));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $stats = $this->getProductStats();
        return view('admin.products.create', $stats);
    }

    /**
     * Show the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $stats = $this->getProductStats();
        
        return view('admin.products.show', array_merge(
            compact('product'),
            $stats
        ));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $stats = $this->getProductStats();
        
        return view('admin.products.edit', array_merge(
            compact('product'),
            $stats
        ));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['product_image'] = $request->file('image')->store('products', 'public');
        }
        
        Product::create($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            $validated['product_image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Update the status of the specified product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Product $product)
    {
        $validated = $request->validate([
            'active' => 'required|boolean',
        ]);
        
        $product->update(['active' => $validated['active']]);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product status updated successfully');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // Delete product image if exists
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}