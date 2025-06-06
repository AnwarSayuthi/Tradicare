<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'appointment_id';
    protected $fillable = ['user_id', 'service_id', 'available_time_id', 'appointment_date', 'status', 'notes'];

    // Add this casts array to convert appointment_date to Carbon instance
    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function availableTime()
    {
        return $this->belongsTo(AvailableTime::class, 'available_time_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'appointment_id');
    }
}