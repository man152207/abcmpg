<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsHoliday extends Model
{
    protected $table = 'us_holidays';

    protected $fillable = [
        'name',
        'date',
        'type',
        'source',
        'state',
        'bank_closed',
        'payment_closed',
        'description',
    ];

    protected $casts = [
        'date'           => 'date',
        'bank_closed'    => 'boolean',
        'payment_closed' => 'boolean',
    ];
}
