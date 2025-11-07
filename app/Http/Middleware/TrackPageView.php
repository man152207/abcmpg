<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\UserActivity;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $admin = auth('admin')->user();
        if (!$admin) return $response;

        $routeName = optional($request->route())->getName();
        $label     = $routeName ?: '/'.$request->path();

        // 1) Refresh detection (same page within 10s)
        $key  = "pv:last:{$admin->id}";
        $last = Cache::get($key);
        $ua   = UserActivity::firstOrCreate(['user_id' => $admin->id]);

        if ($last && $last['label'] === $label && now()->diffInSeconds($last['at']) <= 10) {
            $ua->refresh_rate = (int)$ua->refresh_rate + 1;
        }
        Cache::put($key, ['label' => $label, 'at' => now()], 60);

        // 2) Utility endpoints exclude from frequent pages
        $exclude = ['activity.ping', 'admin.user.checkstatus', 'admin.user.updatelocation'];
        $isExcluded = $routeName ? in_array($routeName, $exclude, true)
                                 : preg_match('#^/(activity/ping|admin/dashboard/user/(check-status|update-location))#', $label);

        if (!$isExcluded) {
            $freq = is_array($ua->frequent_page) ? $ua->frequent_page : (json_decode($ua->frequent_page ?? '[]', true) ?: []);
            $freq[$label] = isset($freq[$label]) ? (int)$freq[$label] + 1 : 1;

            arsort($freq);
            $ua->frequent_page = array_slice($freq, 0, 10, true); // TOP 10 only
        }

        $ua->save();
        return $response;
    }
}
