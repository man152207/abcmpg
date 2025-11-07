<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            // main date of that row (required by controller queries)
            $table->date('duty_date')->after('id');

            // who is on duty that day (controller create() / saveMonth() fills these)
            $table->string('staff1')->nullable()->after('duty_date');
            $table->string('staff2')->nullable()->after('staff1');
            $table->string('staff3')->nullable()->after('staff2');
        });
    }

    public function down(): void
    {
        Schema::table('duty_schedules', function (Blueprint $table) {
            $table->dropColumn(['duty_date', 'staff1', 'staff2', 'staff3']);
        });
    }
};
