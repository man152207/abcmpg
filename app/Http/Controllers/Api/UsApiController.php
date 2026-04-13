<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UsHoliday;
use App\Models\UsTimezone;
use App\Models\UsEmergency;

class UsApiController extends Controller
{
    public function timezones()
    {
        return UsTimezone::orderBy('state')->get();
    }

    public function holidays()
    {
        return UsHoliday::orderBy('date')->get();
    }

    public function bankStatus()
    {
        $today = now()->toDateString();

        $todayHoliday = UsHoliday::where('date', $today)
            ->where('type', 'bank')
            ->first();

        return [
            'date' => $today,
            'is_closed' => $todayHoliday ? true : false,
            'holiday' => $todayHoliday ? $todayHoliday->name : null
        ];
    }

    public function emergency()
    {
        return UsEmergency::orderBy('declared_on')->get();
    }
}
