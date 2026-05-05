<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusService extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'driver_id',
        'description',
        'cost',
        'status',
        'added_by',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
