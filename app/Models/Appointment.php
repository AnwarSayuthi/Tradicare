<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';
    protected $fillable = ['user_id', 'service_id', 'appointment_date', 'end_time', 'status', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'appointment_id');
    }
}

