<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdSetInsight extends Model
{
    use HasFactory;

    protected $table = 'adset_insights'; // Specify the correct table name

    protected $fillable = [
        'customer_id',
        'adset_id',
        'adset_name',
        'impressions',
        'clicks',
        'spend',
        'cpc',
        'cpm',
        'ctr',
        'reach',
        'frequency',
        'date_start',
        'date_stop',
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}