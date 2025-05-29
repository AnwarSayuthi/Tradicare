<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'user_id', 
        'order_id', 
        'appointment_id', 
        'amount', 
        'payment_date', 
        'payment_method', 
        'status', 
        'transaction_id',
        'billcode',
        'bill_reference',
        'payment_details'
    ];
    
    // Remove the timestamps = false since we now want timestamps
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    
    // Payment method constants
    const METHOD_TOYYIBPAY = 'toyyibpay';
    const METHOD_CASH = 'cash';
    const METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';

    protected $casts = [
        'payment_date' => 'datetime',
        'payment_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
    
    /**
     * Check if the payment is for an order
     */
    public function isOrderPayment()
    {
        return !is_null($this->order_id);
    }
    
    /**
     * Check if the payment is for an appointment
     */
    public function isAppointmentPayment()
    {
        return !is_null($this->appointment_id);
    }
    
    /**
     * Check if the payment is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    
    /**
     * Check if the payment is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * Check if the payment is failed
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }
}