<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $primaryKey = 'cart_item_id';
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'unit_price', 'added_at'];
    public $timestamps = false;

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}