<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusClaim;
use App\Models\BonusSeason;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonusClaimController extends Controller
{
    /**
     * Customer ले Admin Panel बाट "Claim Bonus" थिच्दा आउने request
     * Route: POST admin.customers.bonus.claim
     */
    public function claim(Customer $customer, Request $request)
    {
        $data = $request->validate([
            'amount_usd' => ['required', 'numeric', 'min:0.01'],
        ]);

        // 1) active bonus season फेला पार्ने
        $season = BonusSeason::where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        if (!$season) {
            return response()->json([
                'success' => false,
                'message' => 'No active bonus season configured.',
            ], 422);
        }

        // 2) यो customer को यस season को total bonus
        $totalBonus = (float) ($season->calculateBonusForCustomer($customer) ?? 0);

        // 3) पहिले नै यस season मा claim गरेको total
        $alreadyClaimed = BonusClaim::where('customer_id', $customer->id)
            ->where('bonus_season_id', $season->id)
            ->sum('amount_usd');

        $claimable = max($totalBonus - $alreadyClaimed, 0);

        if ($claimable <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No bonus available to claim.',
            ], 422);
        }

        if ($data['amount_usd'] > $claimable) {
            return response()->json([
                'success' => false,
                'message' => 'Requested claim is more than available bonus.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $amount = (float) $data['amount_usd'];

            $claim = BonusClaim::create([
                'customer_id'     => $customer->id,
                'bonus_season_id' => $season->id,

                // ✅ bonus_claims table मा season_code NOT NULL भए भनेर
                'season_code'     => $season->season_code
                    ?? $season->code
                    ?? ('S' . $season->id),

                'amount_usd'      => $amount,
                'mode'            => ($amount == $claimable) ? 'full' : 'partial',

                // ✅ source / claimed_by set (nullable छैन भने पनि error नआओस्)
                'source'          => 'admin_panel',
                'claimed_by'      => Auth::guard('admin')->id() ?? Auth::id() ?? 0,

                // DB schema अनुसार default 'pending' भए पनि explicitly set गर्नु safe
                'status'          => 'pending',

                'claimed_at'      => Carbon::now(),
            ]);

            $newClaimed   = $alreadyClaimed + $amount;
            $newClaimable = max($totalBonus - $newClaimed, 0);

            $status = $newClaimable > 0 ? 'claim_window_open' : 'claimed';

            DB::commit();

            return response()->json([
                'success' => true,
                'data'    => [
                    'season_id'       => $season->id,
                    'season_start'    => $season->start_date?->toDateString(),
                    'season_end'      => $season->end_date?->toDateString(),
                    'total_bonus_usd' => $totalBonus,
                    'claimed_usd'     => $newClaimed,
                    'claimable_usd'   => $newClaimable,
                    'status'          => $status,
                    'can_claim'       => $newClaimable > 0,
                ],
                'claim'   => $claim,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Server error while saving claim.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Admin ले "Mark Completed" मा click गर्दा
     * Route: PATCH admin.bonus-claims.complete
     */
    public function markCompleted(BonusClaim $bonusClaim, Request $request)
    {
        $bonusClaim->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim marked as completed.',
        ]);
    }

    /**
     * Admin ले status change गर्ने (pending → approved/rejected आदि)
     * चाहियो भने यो method implement गर
     */
    public function updateStatus(BonusClaim $claim, Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected,completed'],
        ]);

        $claim->update([
            'status' => $data['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim status updated.',
        ]);
    }
}
