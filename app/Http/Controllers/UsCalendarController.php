<?php

namespace App\Http\Controllers;

use App\Models\UsHoliday;
use App\Models\UsBankStatus;
use App\Models\UsPaymentHoliday;
use App\Models\UsEmergencyClosure;
use Carbon\Carbon;

class UsCalendarController extends Controller
{
    public function index()
    {
        // Base dates
        $today       = Carbon::today();             // आज (server time, but dates ठीक)
        $weekAgo     = $today->copy()->subDays(7);  // ७ दिन अघिसम्म
        $monthAhead  = $today->copy()->addDays(30); // आगामी ३० दिन (Focus: upcoming days)

        $todayStr      = $today->toDateString();
        $weekAgoStr    = $weekAgo->toDateString();
        $monthAheadStr = $monthAhead->toDateString();
        $yesterdayStr  = $today->copy()->subDay()->toDateString();

        // ⏱ Timezones: DB use नगर्ने, खाली collection पठाइदिने
        $times = collect();

        /**
         * 🕒 LIVE US CLOCK (NO DB)
         * मुख्य US timezones:
         * - New York (Eastern)
         * - Chicago (Central)
         * - Denver (Mountain)
         * - Los Angeles (Pacific)
         * - Anchorage (Alaska)
         * - Honolulu (Hawaii)
         */
        $usClock = collect([
            ['label' => 'New York (ET)',    'state' => 'NY', 'timezone' => 'America/New_York'],
            ['label' => 'Chicago (CT)',     'state' => 'IL', 'timezone' => 'America/Chicago'],
            ['label' => 'Denver (MT)',      'state' => 'CO', 'timezone' => 'America/Denver'],
            ['label' => 'Los Angeles (PT)', 'state' => 'CA', 'timezone' => 'America/Los_Angeles'],
            ['label' => 'Anchorage (AK)',   'state' => 'AK', 'timezone' => 'America/Anchorage'],
            ['label' => 'Honolulu (HI)',    'state' => 'HI', 'timezone' => 'Pacific/Honolulu'],
        ])->map(function ($row) {
            $now = Carbon::now($row['timezone']);

            return (object) [
                'label'    => $row['label'],
                'state'    => $row['state'],
                'timezone' => $row['timezone'],
                'time'     => $now->format('h:i A'),     // 03:45 PM
                'date'     => $now->format('M d, Y'),    // Nov 27, 2025
                'day'      => $now->format('D'),         // Thu
            ];
        });

        // 🇺🇸 Federal/Public Holidays (job-test हटाउने)
        $federalUpcoming = UsHoliday::whereBetween('date', [$todayStr, $monthAheadStr])
            ->where(function ($q) {
                $q->whereNull('source')
                  ->orWhere('source', '!=', 'job-test');
            })
            ->orderBy('date')
            ->get();

        $federalRecent = UsHoliday::whereBetween('date', [$weekAgoStr, $yesterdayStr])
            ->where(function ($q) {
                $q->whereNull('source')
                  ->orWhere('source', '!=', 'job-test');
            })
            ->orderBy('date', 'desc')
            ->get();

        // 🏦 Bank Status (DB बाट आउने special closures)
        $bankUpcomingDb = UsBankStatus::whereBetween('date', [$todayStr, $monthAheadStr])
            ->orderBy('date')
            ->get();

        $bankRecentDb = UsBankStatus::whereBetween('date', [$weekAgoStr, $yesterdayStr])
            ->orderBy('date', 'desc')
            ->get();

        // 🏦 Weekend closures: Saturday / Sunday लाई ALL US banks closed मान्ने
        $weekendUpcoming = collect();
        $cursor = $today->copy();
        while ($cursor->lte($monthAhead)) {
            if ($cursor->isWeekend()) {
                $weekendUpcoming->push((object) [
                    'date'       => $cursor->toDateString(),
                    'provider'   => 'US Banks',
                    'status'     => 'closed',
                    'reason'     => $cursor->isSaturday()
                        ? 'Saturday (Weekend)'
                        : 'Sunday (Weekend)',
                    'is_weekend' => true,
                ]);
            }
            $cursor->addDay();
        }

        $weekendRecent = collect();
        $cursor = $weekAgo->copy();
        $endRecent = $today->copy()->subDay();
        while ($cursor->lte($endRecent)) {
            if ($cursor->isWeekend()) {
                $weekendRecent->push((object) [
                    'date'       => $cursor->toDateString(),
                    'provider'   => 'US Banks',
                    'status'     => 'closed',
                    'reason'     => $cursor->isSaturday()
                        ? 'Saturday (Weekend)'
                        : 'Sunday (Weekend)',
                    'is_weekend' => true,
                ]);
            }
            $cursor->addDay();
        }

        // DB bank + weekend closures merge गरेर UI लाई दिने
        $bankUpcomingUi = $bankUpcomingDb
            ->map(function ($b) {
                $b->is_weekend = false;
                return $b;
            })
            ->concat($weekendUpcoming)
            ->sortBy('date')
            ->values();

        $bankRecentUi = $bankRecentDb
            ->map(function ($b) {
                $b->is_weekend = false;
                return $b;
            })
            ->concat($weekendRecent)
            ->sortByDesc('date')
            ->values();

        // 💳 Payment Holidays (PayPal / Relay / Wise / Stripe)
        $paymentUpcoming = UsPaymentHoliday::whereBetween('date', [$todayStr, $monthAheadStr])
            ->orderBy('date')
            ->get();

        $paymentRecent = UsPaymentHoliday::whereBetween('date', [$weekAgoStr, $yesterdayStr])
            ->orderBy('date', 'desc')
            ->get();

        // 🚨 Emergency Closures / Weather Alerts
        $emergencyUpcoming = UsEmergencyClosure::where('date', '>=', $todayStr)
            ->orderBy('date')
            ->get();

        $emergencyRecent = UsEmergencyClosure::whereBetween('date', [$weekAgoStr, $yesterdayStr])
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.uscalendar.index', compact(
            'times',
            'usClock',
            'federalUpcoming', 'federalRecent',
            'bankUpcomingUi', 'bankRecentUi',
            'paymentUpcoming', 'paymentRecent',
            'emergencyUpcoming', 'emergencyRecent'
        ));
    }
}
