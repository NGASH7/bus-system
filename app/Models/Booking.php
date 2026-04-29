<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bus_id',
        'service_type',
        'pickup_location',
        'destination',
        'date',
        'pickup_time',
        'return_date',
        'return_time',
        'offered_price',
        'counter_price',
        'amount',
        'status',
        'details',
        'payment_status',
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
