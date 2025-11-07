<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    // तपाईंले पहिले राखेको $fillable जस्तालाई जस्तै राखिएको छ
    protected $fillable = [
        'external_id','code','name','price','currency','features','active','is_popular','synced_at',
    ];

    protected $casts = [
        'features'   => 'array',
        'active'     => 'boolean',
        'is_popular' => 'boolean',
        'synced_at'  => 'datetime',
    ];

    // NEW: Customer relation (pivot: customer_package)
    public function customers()
    {
        return $this->belongsToMany(\App\Models\Customer::class, 'customer_package')
            ->withPivot(['start_date','end_date','status'])
            ->withTimestamps();
    }
}
