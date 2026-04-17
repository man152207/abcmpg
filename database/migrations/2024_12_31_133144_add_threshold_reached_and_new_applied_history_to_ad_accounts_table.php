<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_accounts', 'threshold_reached_date')) {
                $table->date('threshold_reached_date')->nullable();
            }
            if (!Schema::hasColumn('ad_accounts', 'new_applied_history')) {
                $table->text('new_applied_history')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            $table->dropColumn('threshold_reached_date');
            $table->dropColumn('new_applied_history');
        });
    }
};
