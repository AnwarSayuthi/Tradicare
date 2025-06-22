<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; // Add this line

class Cart extends Model
{
    protected $primaryKey = 'cart_id';
    
    protected $fillable = [
        'user_id',
        'status',
        'order_id'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    /**
     * Calculate the total price of all items in the cart
     *
     * @return float
     */
    public function getTotalPrice()
    {
        $total = $this->cartItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
        
        Log::info('Cart getTotalPrice calculation:', [
            'cart_id' => $this->cart_id,
            'items_count' => $this->cartItems->count(),
            'calculated_total' => $total,
            'items_detail' => $this->cartItems->map(function($item) {
                return [
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->quantity * $item->unit_price
                ];
            })
        ]);
        
        return $total;
    }
}