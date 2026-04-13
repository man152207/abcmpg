<?php

namespace App\Jobs;

use App\Models\UsHoliday;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchUsFederalHolidayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // YEAR dynamic
        $year = now()->year;

        try {
            // TODO: यहाँ वास्तविक API use गर्नुस्
            // यो example मा dummy array राखेको छु
            // बाहिरबाट holiday array ल्याउँदा पनि structure यस्तै मिलाउनुहोस्।
            $holidays = [
                [
                    'name'       => 'New Year\'s Day',
                    'date'       => "$year-01-01",
                    'state'      => 'ALL',
                    'bank_closed'=> true,
                    'pay_closed' => true,
                    'desc'       => 'US Federal Holiday',
                ],
                [
                    'name'       => 'Christmas Day',
                    'date'       => "$year-12-25",
                    'state'      => 'ALL',
                    'bank_closed'=> true,
                    'pay_closed' => true,
                    'desc'       => 'US Federal Holiday',
                ],
            ];

            foreach ($holidays as $h) {
                UsHoliday::updateOrCreate(
                    [
                        'date' => $h['date'],
                        'name' => $h['name'],
                        'type' => 'federal',
                    ],
                    [
                        'source'         => 'fed-holiday-api', // अब 'job-test' छैन
                        'state'          => $h['state'] ?? 'ALL',
                        'bank_closed'    => $h['bank_closed'] ?? false,
                        'payment_closed' => $h['pay_closed'] ?? false,
                        'description'    => $h['desc'] ?? null,
                    ]
                );
            }
        } catch (Throwable $e) {
            // चाहियो भने log गर्नुस्
            logger()->error('FetchUsFederalHolidayJob failed', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
