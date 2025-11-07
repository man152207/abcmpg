<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('duty_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('duty_schedules', 'is_holiday')) {
                $table->boolean('is_holiday')->default(false)->after('helper');
                // वा चाहनु भए 'after' => 'duty_date'
            }
        });
    }
    public function down(): void {
        Schema::table('duty_schedules', function (Blueprint $table) {
            $table->dropColumn('is_holiday');
        });
    }
};
