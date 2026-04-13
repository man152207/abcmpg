<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsEmergencyClosure extends Model
{
    protected $table = 'us_emergency_closures';

    protected $fillable = [
        'date',
        'state',
        'reason',
        'severity',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
