<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Admin activity ping
     * - last_seen cache
     * - active / idle minutes
     * - last_active_start / end
     * - frequent_page tracking (new logic)
     */
    public function ping(Request $r)
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            // पुरानो resp shape + नयाँ status key
            return response()->json([
                'ok'     => false,
                'status' => 'ignored',
            ], 401);
        }

        // पुरानो variable नाम (activeDelta / idleDelta) राख्दै
        $activeDelta = max(0, (int) $r->input('activeDelta', 0)); // seconds
        $idleDelta   = max(0, (int) $r->input('idleDelta', 0));

        // last_seen cache (पुरानो logic)
        Cache::put("admin:last_seen:{$admin->id}", now()->timestamp, 300);

        // existing or new row
        $ua = UserActivity::firstOrCreate(['user_id' => $admin->id]);

        // 👉 seconds बाट minutes (पुरानो जस्तै intdiv)
        $addActiveMin = intdiv($activeDelta, 60);
        $addIdleMin   = intdiv($idleDelta, 60);

        $ua->active_hours  = (int) ($ua->active_hours  ?? 0) + $addActiveMin;
        $ua->inactive_time = (int) ($ua->inactive_time ?? 0) + $addIdleMin;

        // Active Periods last window update (पुरानो logic)
        if ($activeDelta > 0) {
            $ua->last_active_start = now()->subSeconds($activeDelta);
            $ua->last_active_end   = now();
        }

        // 👉 नयाँ frequent_page tracking (path आधारित)
        if ($r->filled('path')) {
            $path = '/' . ltrim($r->input('path'), '/');
            $freq = json_decode($ua->frequent_page, true) ?? [];

            if (!is_array($freq)) {
                $freq = [];
            }

            $freq[$path] = isset($freq[$path]) ? ((int) $freq[$path] + 1) : 1;

            // सबभन्दा धेरै used top 10 paths मात्र राख्ने
            arsort($freq);
            $freq = array_slice($freq, 0, 10, true);

            $ua->frequent_page = json_encode($freq);
        }

        $ua->last_activity = now();
        $ua->save();

        return response()->json([
            'ok'     => true,       // पुरानो
            'status' => 'success',  // नयाँ
        ]);
    }

    /**
     * एक user online छ कि छैन check गर्ने
     */
    public function checkStatus($id)
    {
        $ua = UserActivity::where('user_id', $id)->first();
        $isOnline = $ua && $ua->last_activity
            ? now()->diffInMinutes($ua->last_activity) <= 5
            : false;

        return response()->json(['isOnline' => $isOnline]);
    }

    /**
     * Admin location update
     * - पुरानो version को lat / lng + location string
     * - last_activity पनि update
     */
    public function updateLocation(Request $request)
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return response()->json([
                'ok'     => false,
                'status' => 'ignored',
            ], 401);
        }

        $ua = UserActivity::firstOrNew(['user_id' => $admin->id]);

        if ($request->has(['latitude', 'longitude'])) {
            $lat = $request->input('latitude');
            $lng = $request->input('longitude');

            $ua->latitude  = $lat;
            $ua->longitude = $lng;

            // पुरानो version को location field पनि राख्ने
            if ($lat && $lng) {
                $ua->location = "{$lat},{$lng}";
            }
        }

        $ua->last_activity = now();
        $ua->save();

        return response()->json([
            'ok'     => true,
            'status' => 'success',
        ]);
    }

    /**
     * User activity summary API
     */
    public function getUserActivity($id)
    {
        $ua = UserActivity::firstOrCreate(['user_id' => $id]);
        $freq = json_decode($ua->frequent_page ?? '[]', true) ?: [];

        return response()->json([
            'login_time'       => optional($ua->login_time)->toDateTimeString(),
            'active_minutes'   => (int) ($ua->active_hours ?? 0),
            'inactive_minutes' => (int) ($ua->inactive_time ?? 0),
            'frequent_page'    => $freq,
            'last_activity'    => optional($ua->last_activity)->toDateTimeString(),
        ]);
    }
}
