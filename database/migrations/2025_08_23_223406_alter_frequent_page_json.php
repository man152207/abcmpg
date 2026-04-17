<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_activities')) {
            return;
        }
        // Skip MySQL-specific REGEXP syntax on PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            Schema::table('user_activities', function (Blueprint $table) {
                if (Schema::hasColumn('user_activities', 'frequent_page')) {
                    $table->json('frequent_page')->nullable()->change();
                }
                if (Schema::hasColumn('user_activities', 'active_hours')) {
                    $table->unsignedBigInteger('active_hours')->default(0)->nullable(false)->change();
                }
                if (Schema::hasColumn('user_activities', 'inactive_time')) {
                    $table->unsignedBigInteger('inactive_time')->default(0)->nullable(false)->change();
                }
            });
            return;
        }
    }

    public function down(): void {}
};
