<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'campaign_id',
        'adset_id',
        'ad_id',
        'ad_name',
        'delivery',
        'actions',
        'bid_strategy',
        'budget',
        'last_edit',
        'attribution_setting',
        'results',
        'reach',
        'impressions',
        'cost_per_result',
        'spend',
        'ends',
        'schedule',
        'duration',
        'quality_rank',
        'engagement_rank',
        'conversion_rank'
    ];
}