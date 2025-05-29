<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

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
        return $this->cartItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}