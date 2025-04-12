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

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    // Add this method to the User model class
    public function locations()
    {
        return $this->hasMany(Location::class, 'user_id');
    }

    public function defaultLocation()
    {
        return $this->locations()->where('is_default', true)->first();
    }
}
