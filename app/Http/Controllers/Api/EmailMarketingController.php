<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\DbSql;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailMarketingController extends Controller
{
    public function index(Request $request)
    {
        // ?period=this_week | this_month | this_year | all_time
        $period = $request->get('period', 'this_month');

        $now = Carbon::now();
        $from = null;
        $to   = $now->copy()->endOfDay();

        switch ($period) {
            case 'this_week':
                $from = $now->copy()->startOfWeek();   // Monday
                break;

            case 'this_month':
                $from = $now->copy()->startOfMonth();
                break;

            case 'this_year':
                $from = $now->copy()->startOfYear();
                break;

            case 'all_time':
            default:
                $from = null; // no date filter
                break;
        }

        $query = DB::table('ads')
            ->select(
                'customer',
                DB::raw('COUNT(*) as total_ads'),
                DB::raw(DbSql::alias(DbSql::sumCol('NRP'), 'total_nrp')),
                DB::raw(DbSql::alias(DbSql::sumCol('USD'), 'total_usd')),
                DB::raw('MIN(created_at) as first_ad_at'),
                DB::raw('MAX(created_at) as last_ad_at')
            )
            ->groupBy('customer')
            ->orderByDesc('last_ad_at');

        if ($from) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $rows = $query->get();

        return response()->json([
            'period' => $period,
            'from'   => $from,
            'to'     => $to,
            'data'   => $rows,
        ]);
    }
    public function getAllAds(Request $request)
{
    $query = DB::table('ads')
        ->select(
            'id',
            'customer',
            'USD',
            'Rate',
            'NRP',
            'Ad_Account',
            'Payment',
            'billing_status',
            'Duration',
            'Quantity',
            'Status',
            'Ad_Nature_Page',
            'admin',
            'created_at',
            'updated_at'
        )
        ->orderByDesc('created_at');

    $ads = $query->get();

    return response()->json([
        'success' => true,
        'data' => $ads,
    ]);
}

}
