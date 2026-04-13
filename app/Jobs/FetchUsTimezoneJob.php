<?php

namespace App\Jobs;

use App\Models\UsTimezone;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchUsTimezoneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // चाहिएको state / timezone mapping यहाँ राख्नुस्
        // चाहियो भने पछि extend गर्न मिल्छ
        $timezones = [
            'NY' => 'America/New_York',
            'CA' => 'America/Los_Angeles',
            'TX' => 'America/Chicago',
            'FL' => 'America/New_York',
            'WA' => 'America/Los_Angeles',
        ];

        foreach ($timezones as $state => $tz) {
            $nowInTz = Carbon::now(new DateTimeZone($tz));

            UsTimezone::updateOrCreate(
                ['state' => $state],
                [
                    'timezone'     => $tz,
                    'current_time' => $nowInTz,  // DB मा current_time छ, offset_hours/offset छैन
                ]
            );
        }
    }
}
