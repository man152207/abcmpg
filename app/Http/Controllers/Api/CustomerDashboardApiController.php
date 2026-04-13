<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ad;
use App\Models\CustomerRequirement;
use App\Models\BonusSeason;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CampaignLink;

class CustomerDashboardApiController extends Controller
{
    /**
     * GET /api/customers/{id}/dashboard
     * Dashboard summary (profile + financial + bonus + packages)
     */
    public function dashboard($id)
    {
        try {
            $customer = Customer::with(['packages', 'createdByAdmin'])->findOrFail($id);

            // Financial blocks (spec अनुसार key नाम)
            $allTime   = $this->aggregateAds($customer);
            $thisMonth = $this->aggregateAds($customer, function ($q) {
                $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            });
            $today     = $this->aggregateAds($customer, function ($q) {
                $q->whereDate('created_at', now());
            });

            $bonusData = $this->calculateBonusSeasonData($customer);

            return response()->json([
                'success' => true,
                'data'    => [
                    'profile' => [
                        'id'            => $customer->id,
                        'name'          => $customer->name,
                        'display_name'  => $customer->display_name,
                        'email'         => $customer->email,
                        'phone'         => $customer->phone,
                        'address'       => $customer->address,
                        'avatar'        => $customer->profile_picture
                            ? asset('Uploads/customers/' . $customer->profile_picture)
                            : null,
                        'usd_rate'      => $customer->usd_rate ?? 170,
                        'requires_bill' => $customer->requires_bill,
                        'created_at'    => $customer->created_at?->toISOString(),
                        'created_by'    => optional($customer->createdByAdmin)->name,
                    ],
                    'financial' => [
                        'all_time'   => $allTime,    // {usd, npr, quantity}
                        'this_month' => $thisMonth,  // {usd, npr, quantity}
                        'today'      => $today,      // {usd, npr, quantity}
                    ],
                    'bonus_season' => $bonusData,   // or null
                    'packages'     => $customer->packages->map(function ($pkg) {
                        return [
                            'id'         => $pkg->id,
                            'name'       => $pkg->name,
                            'code'       => $pkg->code,
                            'status'     => $pkg->pivot->status ?? 'active',
                            'start_date' => $pkg->pivot->start_date,
                            'end_date'   => $pkg->pivot->end_date,
                        ];
                    }),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/customers/{id}/notes
     * Customer notes / requirements
     */
    public function notes($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // ✅ तिम्रो existing system अनुसार: CustomerRequirement
            $notes = CustomerRequirement::where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($note) {
                    return [
                        'id'         => $note->id,
                        'body'       => $note->body,
                        'note_type'  => $note->note_type ?? 'requirement',
                        'priority'   => $note->priority ?? 'medium',
                        'created_at' => $note->created_at?->toISOString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => $notes,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notes',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/customers/{id}/receipts?page={page}
     * Simple receipt list (spec अनुसार)
     */
    public function receipts($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $perPage = request()->get('per_page', 5);

            // ✅ Ads table मा customer = phone stored (तिम्रो पुरानो code जस्तै)
            $query = Ad::where('customer', $customer->phone)
                ->orderBy('created_at', 'desc');

            $paginator = $query->paginate($perPage);

            $data = collect($paginator->items())->map(function (Ad $ad) {
                return [
                    'invoice_date'   => $ad->created_at?->toDateString(),
                    'ad_details'     => $ad->Ad_Nature_Page ?? 'Facebook Ad Campaign',
                    'amount_npr'     => (float) $ad->NRP,
                    'payment_status' => $ad->Payment,
                ];
            });

            return response()->json([
                'success'    => true,
                'data'       => $data,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch receipts',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/customers/{id}/monthly-data?offset={offset}
     * 3-3 महिनाको chunk (offset 0 = current+prev2, offset 3 = 3 महिना पछाडि…)
     */
    public function monthlyData(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $offset   = (int) $request->get('offset', 0);

            $monthsData = [];
            $startBase  = now()->subMonths($offset); // offset बाट सुरु

            for ($i = 0; $i < 3; $i++) {
                $month = $startBase->copy()->subMonths($i);

                $usd = (float) Ad::where('customer', $customer->phone)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('USD');

                $npr = (float) Ad::where('customer', $customer->phone)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('NRP');

                $qty = (int) Ad::where('customer', $customer->phone)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('Quantity');

                $monthsData[] = [
                    'month'    => $month->format('F Y'),
                    'usd'      => round($usd, 2),
                    'npr'      => round($npr, 2),
                    'quantity' => $qty,
                ];
            }

            return response()->json([
                'success' => true,
                'data'    => $monthsData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch monthly data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: aggregate ads for given filter
     * Return format: {usd, npr, quantity}
     */
    private function aggregateAds(Customer $customer, ?\Closure $modifier = null): array
    {
        $query = Ad::where('customer', $customer->phone);

        if ($modifier) {
            $modifier($query);
        }

        $usd = (float) $query->sum('USD');
        $npr = (float) $query->sum('NRP');
        $qty = (int) $query->sum('Quantity');

        return [
            'usd'      => round($usd, 2),
            'npr'      => round($npr, 2),
            'quantity' => $qty,
        ];
    }

    /**
     * Helper: Bonus season calculation (तिम्रो existing logic respect गर्दै)
     * Return spec:
     * {
     *   "start_date": "2025-01-01",
     *   "end_date": "2025-03-31",
     *   "bonus_credit": 1500.00,
     *   "breakdown": [
     *     {"month": "January", "amount": 500.00}
     *   ]
     * }
     */
    private function calculateBonusSeasonData(Customer $customer): ?array
    {
        $activeBonusSeason = BonusSeason::where('is_active', true)->first();

        if (!$activeBonusSeason) {
            return null;
        }

        $start = Carbon::parse($activeBonusSeason->start_date)->startOfDay();
        $end   = Carbon::parse($activeBonusSeason->end_date)->endOfDay();

        $percent   = (float) ($activeBonusSeason->bonus_rate ?? 0);  // e.g. 20
        $rate      = $percent / 100;                                // 0.20
        $threshold = (float) ($activeBonusSeason->min_spend ?? 0);  // e.g. 300

        $adTotals = Ad::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, SUM(USD) as total_usd')
            ->where('customer', $customer->phone)
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $bonusCredit = 0;
        $breakdown   = [];

        foreach ($adTotals as $row) {
            if ($row->total_usd >= $threshold && $rate > 0) {
                $monthBonus  = $row->total_usd * $rate;
                $bonusCredit += $monthBonus;

                $monthObj = Carbon::createFromFormat('Y-m-d', $row->ym . '-01');

                $breakdown[] = [
                    'month'  => $monthObj->format('F'),         // e.g. "January"
                    'amount' => round($monthBonus, 2),
                ];
            }
        }

        return [
            'start_date'   => Carbon::parse($activeBonusSeason->start_date)->toDateString(),
            'end_date'     => Carbon::parse($activeBonusSeason->end_date)->toDateString(),
            'bonus_credit' => round($bonusCredit, 2),
            'breakdown'    => $breakdown,
        ];
    }
    
    public function campaignLinks($id)
{
    $customer = Customer::findOrFail($id);

    $links = CampaignLink::where('customer_id', $customer->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($link) {
            return [
                'id'           => $link->id,
                'campaign_link'=> $link->campaign_link,
                'added_at'     => $link->created_at?->toDateTimeString(),
            ];
        });

    return response()->json([
        'success' => true,
        'data'    => $links,
    ]);
}

}
