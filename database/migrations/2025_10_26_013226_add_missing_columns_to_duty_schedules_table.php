<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('duty_schedules', 'duty_date')) {
                $table->date('duty_date')->nullable();
            }
            if (!Schema::hasColumn('duty_schedules', 'staff1')) {
                $table->string('staff1')->nullable();
            }
            if (!Schema::hasColumn('duty_schedules', 'staff2')) {
                $table->string('staff2')->nullable();
            }
            if (!Schema::hasColumn('duty_schedules', 'staff3')) {
                $table->string('staff3')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            $table->dropColumn(['duty_date', 'staff1', 'staff2', 'staff3']);
        });
    }
};
