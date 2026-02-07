<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'detected_class',
        'weight_value',
        'is_stable',
    ];

    protected $casts = [
        'is_stable' => 'boolean',
        'weight_value' => 'float',
    ];

    // Relasi ke IoT Device
    public function device()
    {
        return $this->belongsTo(IotDevice::class, 'device_id', 'device_id');
    }
}