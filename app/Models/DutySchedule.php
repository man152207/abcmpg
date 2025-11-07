<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'duty_date',
        'day_name',

        // legacy text
        'staff1','staff2','staff3',

        // structured ops coverage
        'operations_on',
        'operations_off',
        'covers',

        // optional extra data
        'remarks',          // pipe | joined string
        'production',
        'reception',
        'helper',
        'is_holiday',

        // new
        'shift_overrides',  // json: per-person custom shift for that date
        'preleave_plan',    // json: {Thursday:[], Friday:[]}
    ];

    protected $casts = [
        'operations_on'   => 'array',
        'operations_off'  => 'array',
        'covers'          => 'array',
        'is_holiday'      => 'boolean',

        'shift_overrides' => 'array',
        'preleave_plan'   => 'array',
    ];
}
