<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusClaim extends Model
{
    protected $fillable = [
        'customer_id',
        'bonus_season_id',
        'amount_usd',
        'mode',
        'source',
        'claimed_by',
        'status',
        'claimed_at',
        'season_code',
        'completed_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];
    protected $dates = [
        'claimed_at',
        'completed_at',
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function season()
    {
        return $this->belongsTo(BonusSeason::class, 'bonus_season_id');
    }
}
