<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnavailableTime extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'unavailable_time_id';
    protected $fillable = ['available_time_id', 'date'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];
    
    public function availableTime()
    {
        return $this->belongsTo(AvailableTime::class, 'available_time_id');
    }
}