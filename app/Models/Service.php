<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'service_id';
    protected $fillable = ['service_name', 'description', 'duration_minutes', 'price', 'active', 'deleted'];
    
    // CRITICAL FIX: Enable timestamps since migration creates them
    public $timestamps = true;
    
    // Cast attributes to proper types
    protected $casts = [
        'active' => 'boolean',
        'deleted' => 'boolean',
        'price' => 'decimal:2',
        'duration_minutes' => 'integer'
    ];
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'service_id');
    }
    
    // Scope to exclude deleted services
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', 0);
    }
}