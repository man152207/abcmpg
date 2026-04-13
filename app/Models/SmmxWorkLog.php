<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmxWorkLog extends Model
{
    use HasFactory;

    protected $table = 'smmx_work_logs';

    protected $fillable = [
        'customer_id',
        'onboarding_id',
        'deliverable_id',
        'work_date',
        'report_month',
        'report_year',
        'work_type',
        'title',
        'description',
        'quantity',
        'status',
        'assigned_to',
        'asset_link',
        'external_link',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }

    public function onboarding()
    {
        return $this->belongsTo(\App\Models\SmmxOnboarding::class, 'onboarding_id');
    }

    public function deliverable()
    {
        return $this->belongsTo(\App\Models\SmmxDeliverable::class, 'deliverable_id');
    }
}