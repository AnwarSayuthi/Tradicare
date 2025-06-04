<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';
    protected $primaryKey = 'tracking_id';
    
    protected $fillable = [
        'tracking_number',
        'order_id'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}