<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'service_id';
    protected $fillable = ['service_name', 'description', 'duration_minutes', 'price', 'active'];
    public $timestamps = false;

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'service_id');
    }
}