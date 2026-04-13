<?php

namespace App\Jobs;

use App\Models\UsPaymentHoliday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchUsPaymentHolidayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $year = now()->year;

        // चाहियो भने providers dynamic / API बाट ल्याउनुस्
        $paymentHolidays = [
            [
                'provider'    => 'PayPal',
                'date'        => "$year-12-25",
                'status'      => 'closed',
                'description' => 'Christmas - PayPal Closed',
            ],
            [
                'provider'    => 'Relay',
                'date'        => "$year-12-25",
                'status'      => 'closed',
                'description' => 'Christmas - Relay Closed',
            ],
            [
                'provider'    => 'Wise',
                'date'        => "$year-12-25",
                'status'      => 'closed',
                'description' => 'Christmas - Wise Closed',
            ],
        ];

        foreach ($paymentHolidays as $row) {
            UsPaymentHoliday::updateOrCreate(
                [
                    'provider' => $row['provider'],
                    'date'     => $row['date'],
                ],
                [
                    'status'      => $row['status'],
                    'description' => $row['description'],
                ]
            );
        }
    }
}
