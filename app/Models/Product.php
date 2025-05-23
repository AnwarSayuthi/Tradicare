<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'product_name',
        'price',
        'description',
        'stock_quantity',
        'category',
        'product_image',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
    
    // New methods for better category handling
    
    /**
     * Get all active categories with product counts
     *
     * @return array
     */
    public static function getActiveCategories()
    {
        return self::where('active', true)
            ->select('category')
            ->selectRaw('COUNT(*) as product_count')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category => $item->product_count];
            })
            ->toArray();
    }
    
    /**
     * Get all categories with product counts (including inactive)
     *
     * @return array
     */
    public static function getAllCategories()
    {
        return self::select('category')
            ->selectRaw('COUNT(*) as product_count')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category => $item->product_count];
            })
            ->toArray();
    }
    
    /**
     * Get related products in the same category
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedProducts($limit = 4)
    {
        return self::where('category', $this->category)
            ->where('product_id', '!=', $this->product_id)
            ->where('active', true)
            ->limit($limit)
            ->get();
    }
    
    /**
     * Reset the auto-increment value to the next available ID
     * 
     * @return bool
     */
    public static function resetAutoIncrement()
    {
        $maxId = self::max('product_id');
        $nextId = $maxId ? $maxId + 1 : 1;
        
        return DB::statement("ALTER TABLE products AUTO_INCREMENT = {$nextId}");
    }
    
    /**
     * Get the product image URL with cache busting
     *
     * @return string|null
     */
    public function getImageUrl()
    {
        if (!$this->product_image) {
            return null;
        }

        $imagePath = 'storage/' . $this->product_image;
        
        // Add cache busting parameter using file modification time
        if (file_exists(public_path($imagePath))) {
            $timestamp = filemtime(public_path($imagePath));
            return asset($imagePath) . '?v=' . $timestamp;
        }
        
        // Fallback to regular asset URL if file doesn't exist
        return asset($imagePath);
    }
}