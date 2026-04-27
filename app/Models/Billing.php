<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount_due',
        'due_date',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
