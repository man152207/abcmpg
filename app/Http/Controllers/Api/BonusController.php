<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BonusSeason;
use App\Models\BonusClaim;
use App\Models\Customer;
use App\Models\Ad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    /**
     * GET /api/customers/{customer}/bonus-summary
     */
    public function summary(Customer $customer)
    {
        $now       = now();
        $todayDate = Carbon::today();

        // पहिले active season, नभए last season
        $season = BonusSeason::where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        if (!$season) {
            $season = BonusSeason::orderByDesc('start_date')->first();
        }

        // कुनै season नै छैन भने
        if (!$season) {
            return response()->json([
                'success' => true,
                'data' => [
                    'status'          => 'none',
                    'can_claim'       => false,
                    'has_claimed'     => false,
                    'info_message'    => 'No bonus season configured.',

                    'bonus_percent'   => 0,
                    'min_spend'       => 0,
                    'total_spend_usd' => 0,

                    'total_bonus_usd' => 0,
                    'claimed_usd'     => 0,
                    'claimable_usd'   => 0,

                    'season_id'       => null,
                    'season_start'    => null,
                    'season_end'      => null,
                    'claim_deadline'  => null,
                    'claim_days'      => 0,
                ],
            ]);
        }

        // start / end लाई Carbon मा normalize
        $start = $season->start_date instanceof Carbon
            ? $season->start_date->copy()->startOfDay()
            : Carbon::parse($season->start_date)->startOfDay();

        $end = $season->end_date instanceof Carbon
            ? $season->end_date->copy()->endOfDay()
            : Carbon::parse($season->end_date)->endOfDay();

        // configurable claim days (default 7)
        $claimDays     = $season->claim_days ?? 7;
        $claimDeadline = $end->copy()->addDays($claimDays)->endOfDay();

        // यस season भित्रको ग्राहकको total USD spend (phone field बाट)
        $totalSpendUsd = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [
                $start->copy()->startOfDay(),
                $end->copy()->endOfDay(),
            ])
            ->sum('USD');

        $bonusRate = $season->bonus_rate ?? 1;  // % मा
        $minSpend  = $season->min_spend ?? 0;   // USD

        if ($minSpend > 0 && $totalSpendUsd < $minSpend) {
            // minimum spend नपुग्दा bonus 0
            $totalBonusUsd = 0;
        } else {
            $totalBonusUsd = round($totalSpendUsd * ($bonusRate / 100), 2);
        }

        // यस season मा पहिले claim गरेको bonus
        $claimedUsd = BonusClaim::where('customer_id', $customer->id)
            ->where('bonus_season_id', $season->id)
            ->sum('amount_usd');

        $claimableUsd = max($totalBonusUsd - $claimedUsd, 0);
        $hasClaim     = $claimedUsd > 0;

        // season status निकाल्ने (date-level logic)
        $status      = 'running';
        $canClaim    = false;
        $infoMessage = null;

        if ($todayDate->lt($start)) {
            // season सुरु नै भएको छैन
            $status      = 'upcoming';
            $infoMessage = 'Bonus season starts on ' . $start->toFormattedDateString() . '.';
        } elseif ($todayDate->between($start, $end)) {
            // season running (start–end बीच)
            $status      = 'running';
            $infoMessage = 'Bonus season is currently running. Claim will open after ' .
                $end->toFormattedDateString() .
                '. You will have ' . $claimDays . ' days to claim.';
        } elseif ($todayDate->lte($claimDeadline)) {
            // season सकिसकेको, claim window भित्र
            $status      = 'claim_window_open';
            $canClaim    = $claimableUsd > 0;
            $infoMessage = 'You can claim your bonus until ' .
                $claimDeadline->toFormattedDateString() .
                ' (within ' . $claimDays . ' days after season end).';
        } else {
            // claim window expired
            $status      = 'expired';
            $infoMessage = 'Claim window closed on ' . $claimDeadline->toFormattedDateString() . '.';
        }

        /**
         * IMPORTANT: एक पटक पनि claim भइसकेपछि:
         * - status = "claimed"
         * - can_claim = false
         * - claimable_usd = 0
         */
        if ($hasClaim) {
            $status        = 'claimed';
            $canClaim      = false;
            $claimableUsd  = 0;
            $infoMessage   = $infoMessage ?? 'You have already claimed your bonus for this season.';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'season_id'       => $season->id,
                'status'          => $status,
                'can_claim'       => $canClaim,
                'has_claimed'     => $hasClaim,
                'info_message'    => $infoMessage,

                'bonus_percent'   => $bonusRate,
                'min_spend'       => $minSpend,
                'total_spend_usd' => $totalSpendUsd,

                'total_bonus_usd' => $totalBonusUsd,
                'claimed_usd'     => $claimedUsd,
                'claimable_usd'   => $claimableUsd,

                'season_start'    => $start->toDateString(),
                'season_end'      => $end->toDateString(),
                'claim_deadline'  => $claimDeadline->toDateString(),
                'claim_days'      => $claimDays,
            ],
        ]);
    }

    /**
     * पुरानो API claim
     * POST /api/customers/{customer}/bonus-claim
     */
    public function claim(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'bonus_season_id' => 'required|exists:bonus_seasons,id',
            'amount_usd'      => 'required|numeric|min:0.01',
            'mode'            => 'required|in:full,partial',
        ]);

        $season = BonusSeason::findOrFail($validated['bonus_season_id']);
        $today  = now();

        // start / end normalize
        $start = $season->start_date instanceof Carbon
            ? $season->start_date->copy()->startOfDay()
            : Carbon::parse($season->start_date)->startOfDay();

        $end = $season->end_date instanceof Carbon
            ? $season->end_date->copy()->endOfDay()
            : Carbon::parse($season->end_date)->endOfDay();

        $claimDays     = $season->claim_days ?? 7;
        $claimDeadline = $end->copy()->addDays($claimDays)->endOfDay();

        // केवल claim_window_open भित्र मात्र (season end पछि मात्र)
        if (!$today->gt($end) || !$today->lte($claimDeadline)) {
            return response()->json([
                'success' => false,
                'message' => 'Bonus season is not in claim window.',
            ], 422);
        }

        // सुरक्षित check: पुन: calculation
        $totalSpendUsd = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [
                $start->copy()->startOfDay(),
                $end->copy()->endOfDay(),
            ])
            ->sum('USD');

        $bonusRate = $season->bonus_rate ?? 1;
        $minSpend  = $season->min_spend ?? 0;

        if ($minSpend > 0 && $totalSpendUsd < $minSpend) {
            $totalBonusUsd = 0;
        } else {
            $totalBonusUsd = round($totalSpendUsd * ($bonusRate / 100), 2);
        }

        $claimedUsd = BonusClaim::where('customer_id', $customer->id)
            ->where('bonus_season_id', $season->id)
            ->sum('amount_usd');

        $claimableUsd = max($totalBonusUsd - $claimedUsd, 0);

        if ($claimableUsd <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No bonus left to claim.',
            ], 422);
        }

        $amount = (float) $validated['amount_usd'];

        if ($amount > $claimableUsd) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot claim more than available bonus.',
            ], 422);
        }

        $isFull = $amount >= $claimableUsd - 0.0001;

        BonusClaim::create([
            'customer_id'     => $customer->id,
            'bonus_season_id' => $season->id,
            'amount_usd'      => $amount,
            'status'          => 'pending',
            'mode'            => $validated['mode'],
            'claimed_at'      => now(),
            'season_code'     => $season->season_code ?? $season->code ?? ('S' . $season->id),
        ]);

        return response()->json([
            'success'        => true,
            'message'        => 'Bonus claim recorded.',
            'claimed_amount' => $amount,
            'is_full'        => $isFull,
        ]);
    }

    /**
     * GET /api/customers/{customer}/bonus-claims
     * Customer को सबै season को claim history
     */
    public function claims(Customer $customer)
    {
        $claims = BonusClaim::with('season')
            ->where('customer_id', $customer->id)
            ->orderByDesc('claimed_at')
            ->get();

        $data = $claims->map(function (BonusClaim $claim) {
            $season = $claim->season;

            $claimedAt   = $claim->claimed_at
                ? Carbon::parse($claim->claimed_at)->toDateTimeString()
                : null;

            $completedAt = $claim->completed_at
                ? Carbon::parse($claim->completed_at)->toDateTimeString()
                : null;

            $seasonStart = ($season && $season->start_date)
                ? Carbon::parse($season->start_date)->toDateString()
                : null;

            $seasonEnd = ($season && $season->end_date)
                ? Carbon::parse($season->end_date)->toDateString()
                : null;

            return [
                'id'           => $claim->id,
                'amount_usd'   => (float) $claim->amount_usd,
                'mode'         => $claim->mode,
                'status'       => $claim->status,
                'claimed_at'   => $claimedAt,
                'completed_at' => $completedAt,
                'season'       => [
                    'id'          => $season->id ?? null,
                    'season_code' => $season->season_code ?? null,
                    'start_date'  => $seasonStart,
                    'end_date'    => $seasonEnd,
                ],
            ];
        });

        $total = (float) $claims->sum('amount_usd');

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $data,
                'totals' => [
                    'total_claimed_all_seasons' => $total,
                ],
            ],
        ]);
    }
}
