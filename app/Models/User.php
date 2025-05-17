<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tel_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'user_id');
    }

    public function defaultLocation()
    {
        return $this->locations()->where('is_default', true)->first();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }
}
