<?php

namespace App\Jobs;

use App\Models\UsBankStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchUsBankStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = now()->toDateString();

        $statuses = [
            [
                'provider' => 'FederalReserve',
                'status'   => 'open',
                'reason'   => 'Normal Business Day',
            ],
        ];

        foreach ($statuses as $s) {
            UsBankStatus::updateOrCreate(
                [
                    'provider' => $s['provider'],
                    'date'     => $today,
                ],
                [
                    'status' => $s['status'],
                    'reason' => $s['reason'],
                ]
            );
        }
    }
}
