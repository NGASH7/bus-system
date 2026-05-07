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
        'payment_method',
        'payer_phone',
        'payment_reference',
        'mpesa_checkout_request_id',
        'mpesa_merchant_request_id',
        'paid_at',
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
