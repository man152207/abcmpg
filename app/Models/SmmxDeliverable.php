<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmxDeliverable extends Model
{
    use HasFactory;

    protected $table = 'smmx_deliverables';

    protected $fillable = [
        'customer_id',
        'package_id',
        'onboarding_id',
        'report_month',
        'report_year',
        'posts_planned',
        'posts_completed',
        'graphics_planned',
        'graphics_completed',
        'reels_planned',
        'reels_completed',
        'stories_planned',
        'stories_completed',
        'ad_spend_planned',
        'ad_spend_used',
        'campaign_objective',
        'approval_status',
        'assigned_staff',
        'planned_date',
        'published_date',
        'asset_links',
        'pending_items',
        'next_action',
        'notes',
        'report_sent',
        'status',
    ];

    protected $casts = [
        'assigned_staff' => 'array',
        'asset_links' => 'array',
        'planned_date' => 'date',
        'published_date' => 'date',
        'report_sent' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id');
    }

    public function onboarding()
    {
        return $this->belongsTo(SmmxOnboarding::class, 'onboarding_id');
    }

    public function report()
    {
        return $this->hasOne(SmmxMonthlyReport::class, 'deliverable_id');
    }

    public function getCompletionRateAttribute()
    {
        $planned = (int)$this->posts_planned + (int)$this->graphics_planned + (int)$this->reels_planned + (int)$this->stories_planned;
        $completed = (int)$this->posts_completed + (int)$this->graphics_completed + (int)$this->reels_completed + (int)$this->stories_completed;

        if ($planned <= 0) {
            return 0;
        }

        return round(($completed / $planned) * 100, 2);
    }
}