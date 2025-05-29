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
        'seller_message'
    ];
    public $timestamps = false;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    
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

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
    
    /**
     * Get all cart items associated with this order
     */
    public function items()
    {
        return $this->cart ? $this->cart->cartItems : collect([]);
    }
    
    /**
     * Check if the order is paid
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }
    
    /**
     * Check if the order is cancelled
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}