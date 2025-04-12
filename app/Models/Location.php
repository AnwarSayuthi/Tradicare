<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $primaryKey = 'location_id';
    
    protected $fillable = [
        'user_id',
        'location_name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'phone_number',
        'is_default',
        'is_pickup_address',
        'is_return_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        
        return $address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
    }
}