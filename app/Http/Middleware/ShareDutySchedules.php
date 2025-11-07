<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\DutySchedule;
use Carbon\Carbon;

class ShareDutySchedules
{
    public function handle($request, Closure $next)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Cache results for 60 seconds to prevent too many DB connections
        $dutyToday = Cache::remember('duty_today_' . $today->toDateString(), 60, function () use ($today) {
            return DutySchedule::whereDate('duty_date', $today)->get();
        });

        $dutyTomorrow = Cache::remember('duty_tomorrow_' . $tomorrow->toDateString(), 60, function () use ($tomorrow) {
            return DutySchedule::whereDate('duty_date', $tomorrow)->get();
        });

        View::share('dutyToday', $dutyToday);
        View::share('dutyTomorrow', $dutyTomorrow);

        return $next($request);
    }
}
