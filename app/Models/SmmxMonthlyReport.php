<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmxMonthlyReport extends Model
{
    use HasFactory;

    protected $table = 'smmx_monthly_reports';

    protected $fillable = [
        'customer_id',
        'deliverable_id',
        'report_month',
        'report_year',
        'total_reach',
        'total_impressions',
        'total_leads',
        'total_messages',
        'total_spend',
        'completion_rate',
        'best_performing_content',
        'summary_remark',
        'report_status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }

    public function deliverable()
    {
        return $this->belongsTo(SmmxDeliverable::class, 'deliverable_id');
    }
}