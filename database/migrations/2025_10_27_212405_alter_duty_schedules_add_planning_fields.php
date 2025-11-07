<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            // cached weekday label like "Monday"
            if (!Schema::hasColumn('duty_schedules', 'day_name')) {
                // we know you already added 'duty_date' in older migration,
                // so we can safely place after that
                $table->string('day_name', 20)->nullable()->after('duty_date');
            }

            // per-day custom shift like:
            // { "Kalpana Ghale": "11:00-17:00", "Sharu Tamang":"10:00-18:00" }
            if (!Schema::hasColumn('duty_schedules', 'shift_overrides')) {
                $table->json('shift_overrides')->nullable()->after('covers');
            }

            // pre-leave planning for Sat duties, e.g.:
            // { "Thursday":["Kalpana Ghale"], "Friday":["Prakriti Shrestha"] }
            if (!Schema::hasColumn('duty_schedules', 'preleave_plan')) {
                $table->json('preleave_plan')->nullable()->after('shift_overrides');
            }
        });
    }

    public function down(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('duty_schedules', 'preleave_plan')) {
                $table->dropColumn('preleave_plan');
            }
            if (Schema::hasColumn('duty_schedules', 'shift_overrides')) {
                $table->dropColumn('shift_overrides');
            }
            if (Schema::hasColumn('duty_schedules', 'day_name')) {
                $table->dropColumn('day_name');
            }
        });
    }
};
