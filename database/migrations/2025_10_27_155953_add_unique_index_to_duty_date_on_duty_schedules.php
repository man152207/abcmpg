<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('duty_schedules', 'duty_date')) {
                $table->unique('duty_date', 'duty_schedules_duty_date_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('duty_schedules', 'duty_date')) {
                $table->dropUnique('duty_schedules_duty_date_unique');
            }
        });
    }
};
