<?php

namespace App\Jobs;

use App\Models\UsEmergencyClosure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchUsEmergencyClosureJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = now()->toDateString();

        // TODO: यहाँ वास्तविक weather / emergency API integrate गर्न सकिन्छ
        // अहिलेको लागि example मात्र
        $closures = [
            [
                'date'     => $today,
                'state'    => 'NY',
                'reason'   => 'Lake Effect Snow Warning',
                'severity' => 'Extreme',
            ],
            [
                'date'     => $today,
                'state'    => 'CA',
                'reason'   => 'Dense Fog Advisory',
                'severity' => 'Moderate',
            ],
        ];

        foreach ($closures as $c) {
            UsEmergencyClosure::updateOrCreate(
                [
                    'date'  => $c['date'],
                    'state' => $c['state'],
                ],
                [
                    'reason'   => $c['reason'],
                    'severity' => $c['severity'],
                ]
            );
        }
    }
}
