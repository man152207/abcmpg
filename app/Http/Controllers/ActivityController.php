<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function ping(Request $r)
    {
        $admin = auth('admin')->user();
        if (!$admin) return response()->json(['ok'=>false], 401);

        $activeDelta = max(0, (int)$r->input('activeDelta', 0)); // seconds
        $idleDelta   = max(0, (int)$r->input('idleDelta', 0));

        Cache::put("admin:last_seen:{$admin->id}", now()->timestamp, 300);

        $ua = UserActivity::firstOrCreate(['user_id' => $admin->id]);

        // minutes मा स्टोर (integer)
        $ua->active_hours  = (int)$ua->active_hours  + intdiv($activeDelta, 60);
        $ua->inactive_time = (int)$ua->inactive_time + intdiv($idleDelta, 60);

        // Active Periods last window update
        if ($activeDelta > 0) {
            $ua->last_active_start = now()->subSeconds($activeDelta);
            $ua->last_active_end   = now();
        }

        $ua->last_activity = now();
        $ua->save();

        return response()->json(['ok'=>true]);
    }

    public function checkStatus($id)
    {
        $ua = UserActivity::where('user_id',$id)->first();
        $isOnline = $ua && $ua->last_activity
                  ? now()->diffInMinutes($ua->last_activity) <= 5
                  : false;
        return response()->json(['isOnline' => $isOnline]);
    }

        public function updateLocation(Request $request)
    {
        $userId = Auth::guard('admin')->id();
        if (!$userId) return response()->json(['status'=>'ignored'], 200);

        $ua = UserActivity::firstOrNew(['user_id' => $userId]);
        if ($request->has(['latitude','longitude'])) {
            $ua->latitude  = $request->input('latitude');
            $ua->longitude = $request->input('longitude');
        }
        $ua->last_activity = now();
        $ua->save();

        return response()->json(['status'=>'success']);
    }
        public function getUserActivity($id)
    {
        $ua = UserActivity::firstOrCreate(['user_id' => $id]);
        $freq = json_decode($ua->frequent_page ?? '[]', true) ?: [];
        return response()->json([
            'login_time'       => optional($ua->login_time)->toDateTimeString(),
            'active_minutes'   => (int)($ua->active_hours ?? 0),
            'inactive_minutes' => (int)($ua->inactive_time ?? 0),
            'frequent_page'    => $freq,
            'last_activity'    => optional($ua->last_activity)->toDateTimeString(),
        ]);
    }

    public function updateLocation(Request $r)
    {
        $admin = auth('admin')->user();
        if (!$admin) return response()->json(['ok'=>false], 401);

        $ua = UserActivity::firstOrCreate(['user_id' => $admin->id]);
        $lat = $r->input('latitude'); $lng = $r->input('longitude');
        $ua->latitude = $lat; $ua->longitude = $lng;
        $ua->location = ($lat && $lng) ? "{$lat},{$lng}" : $ua->location;
        $ua->save();

        return response()->json(['ok'=>true]);
    }
        public function ping(Request $request)
    {
        $userId = Auth::guard('admin')->id();
        if (!$userId) return response()->json(['status'=>'ignored'], 200);

        $ua = UserActivity::firstOrNew(['user_id' => $userId]);

        $activeDeltaSec = (int) $request->input('activeDelta', 0);
        $idleDeltaSec   = (int) $request->input('idleDelta', 0);

        if ($activeDeltaSec > 0 || $idleDeltaSec > 0) {
            $addActiveMin = (int) round($activeDeltaSec / 60);
            $addIdleMin   = (int) round($idleDeltaSec / 60);

            $ua->active_hours  = (int)($ua->active_hours ?? 0) + $addActiveMin;
            $ua->inactive_time = (int)($ua->inactive_time ?? 0) + $addIdleMin;

            if ($request->filled('path')) {
                $path = '/'.ltrim($request->input('path'), '/');
                $freq = json_decode($ua->frequent_page, true) ?? [];
                if (!is_array($freq)) $freq = [];
                $freq[$path] = isset($freq[$path]) ? ((int)$freq[$path] + 1) : 1;
                arsort($freq);
                $freq = array_slice($freq, 0, 10, true);
                $ua->frequent_page = json_encode($freq);
            }
        }

        $ua->last_activity = now();
        $ua->save();

        return response()->json(['status' => 'success']);
    }


}
