<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Ad;          // ✅ यो थपिदिनु

class BonusSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'bonus_rate',   // percent (e.g. 1, 5, 10)
        'min_spend',    // USD मा minimum spend
        'claim_days',   // season end पछि कति दिन claim गर्न पाइने
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
        'bonus_rate' => 'decimal:2',
        'min_spend'  => 'decimal:2',
        'claim_days' => 'integer',
    ];

    /**
     * यो season भित्र particular customer ले कति BONUS पाउँछ?
     * Logic: season भित्रको total USD spend * bonus_rate%
     */
    public function calculateBonusForCustomer(\App\Models\Customer $customer): float
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $start = Carbon::parse($this->start_date)->startOfDay();
        $end   = Carbon::parse($this->end_date)->endOfDay();

        // ✅ Ads table मा "customer" = phone, "USD" = amount
        $totalUsd = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [$start, $end])
            ->sum('USD');

        $totalUsd = (float) $totalUsd;

        // minimum spend check
        if (!is_null($this->min_spend) && $totalUsd < (float) $this->min_spend) {
            return 0;
        }

        $rate = (float) ($this->bonus_rate ?? 0); // e.g. 5 = 5%

        if ($rate <= 0 || $totalUsd <= 0) {
            return 0;
        }

        $bonus = $totalUsd * ($rate / 100);

        return round($bonus, 2);
    }
}
