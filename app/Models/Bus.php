<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'model',
        'capacity',
        'insurance_expiry',
        'license_expiry',
        'photo_path',
        'current_location_lat',
        'current_location_lng',
        'is_active',
        'driver_id',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    protected $casts = [
        'is_active' => 'boolean',
        'insurance_expiry' => 'date',
        'license_expiry' => 'date',
    ];
}
