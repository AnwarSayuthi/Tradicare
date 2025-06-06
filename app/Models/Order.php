<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'user_id', 
        'location_id',
        'order_date', 
        'total_amount', 
        'payment_status', 
        'status',
    ];
    public $timestamps = false;

    // Add this casts array to convert order_date to Carbon instance
    protected $casts = [
        'order_date' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    
    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }
    
    /**
     * Get all cart items associated with this order through the cart
     */
    public function items()
    {
        return $this->hasManyThrough(
            CartItem::class,
            Cart::class,
            'order_id', // Foreign key on carts table
            'cart_id',  // Foreign key on cart_items table
            'order_id', // Local key on orders table
            'cart_id'   // Local key on carts table
        );
    }

    public function tracking()
    {
        return $this->hasOne(Tracking::class, 'order_id', 'order_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }
}