<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsPaymentHoliday extends Model
{
    protected $table = 'us_payment_holidays';

    protected $fillable = [
        'provider',
        'date',
        'status',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
