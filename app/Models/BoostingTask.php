<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoostingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'requested_time',
        'eta_time',
        'priority',
        'remarks',
        'dispatcher_id',
        'assigned_to',
        'status',
        'completed_time',
    ];

    // Use $casts for datetime fields (replaces deprecated $dates)
    protected $casts = [
        'requested_time' => 'datetime',
        'eta_time' => 'datetime',
        'completed_time' => 'datetime',
    ];

    public function dispatcher() {
        return $this->belongsTo(Admin::class, 'dispatcher_id');
    }

    public function assignedUser() {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }
}