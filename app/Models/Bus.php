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
        'current_location_lat',
        'current_location_lng',
        'is_active',
    ];
}
