<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsBankStatus extends Model
{
    protected $table = 'us_bank_statuses';

    protected $fillable = [
        'provider',
        'date',
        'status',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
