<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvailableTime extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'available_time_id';
    protected $fillable = ['start_time', 'end_time'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'available_time_id');
    }
    
    public function unavailableTimes()
    {
        return $this->hasMany(UnavailableTime::class, 'available_time_id');
    }
}