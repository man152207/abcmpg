<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    protected $fillable = [
        'admin_id', // यो पनि add गर्नुपर्छ
        'log_date',
        'production',
        'reception',
        'operations',
        'summary',
        'status',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    // किसले submit गर्यो भन्ने सम्बन्ध
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }

    // Helper accessors: DB मा TEXT (JSON), app मा array
    public function getProductionArrayAttribute()
    {
        return json_decode($this->production ?? '[]', true) ?: [];
    }

    public function getReceptionArrayAttribute()
    {
        return json_decode($this->reception ?? '[]', true) ?: [];
    }

    public function getOperationsArrayAttribute()
    {
        return json_decode($this->operations ?? '[]', true) ?: [];
    }
}
