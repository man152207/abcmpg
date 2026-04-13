<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// ========== ADD THESE 5 LINES ==========
use App\Jobs\FetchUsTimezoneJob;
use App\Jobs\FetchUsFederalHolidayJob;
use App\Jobs\FetchUsFedBankHolidayJob;
use App\Jobs\FetchUsPaymentHolidayJob;
use App\Jobs\FetchUsEmergencyAlertJob;
// ========================================

class Kernel extends ConsoleKernel
{
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
    {
        // Your existing schedules
        $schedule->call(function () {
            \DB::table('ads')
                ->whereRaw('DATE_ADD(created_at, INTERVAL Duration DAY) <= NOW()')
                ->update(['is_complete' => 1]);
        })->everyMinute();

        $schedule->call(function () {
            \DB::table('ads')
                ->whereRaw('DATE_ADD(created_at, INTERVAL Duration DAY) > NOW()')
                ->update(['is_complete' => 0]);
        })->everyMinute();

        // NEW USA CALENDAR JOBS
        // Timezone हरु refresh – हरेक घण्टा
    $schedule->job(new FetchUsTimezoneJob())->hourly();

    // Federal holidays – दिनमा एक पटक (मर्निङ)
    $schedule->job(new FetchUsFederalHolidayJob())->dailyAt('03:00');

    // Payment holidays – दिनमा एक पटक
    $schedule->job(new FetchUsPaymentHolidayJob())->dailyAt('03:30');

    // Emergency closures – हरेक 2 घण्टामा
    $schedule->job(new FetchUsEmergencyClosureJob())->everyTwoHours();

    // Bank statuses – दिनमा एक पटक
    $schedule->job(new FetchUsBankStatusJob())->dailyAt('04:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
