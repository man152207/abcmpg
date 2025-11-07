<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            // Update is_complete for ads where (created_at + duration) <= now
            \DB::table('ads')
                ->whereRaw('DATE_ADD(created_at, INTERVAL Duration DAY) <= NOW()')
                ->update(['is_complete' => 1]);
        })->everyMinute();
        $schedule->command('mpg:sync-packages')->everyFifteenMinutes()->withoutOverlapping();
        $schedule->call(function () {

            \DB::table('ads')
                ->whereRaw('DATE_ADD(created_at, INTERVAL Duration Day) > Now()')
                ->update(['is_complete' => 0]);
        })->everyMinute();
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
