<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsTimezone extends Model
{
    protected $table = 'us_timezones';

    protected $fillable = [
        'state',
        'timezone',
        'current_time',
    ];

    protected $casts = [
        'current_time' => 'datetime',
    ];
}
