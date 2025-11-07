<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThresholdReachedAndNewAppliedHistoryToAdAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            $table->date('threshold_reached_date')->nullable()->after('active_since');
            $table->text('new_applied_history')->nullable()->after('targeted_budget');
        });
    }

    public function down()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            $table->dropColumn('threshold_reached_date');
            $table->dropColumn('new_applied_history');
        });
    }
}