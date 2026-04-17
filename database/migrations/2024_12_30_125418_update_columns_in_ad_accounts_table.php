<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('ad_accounts', 'current_threshold') && !Schema::hasColumn('ad_accounts', 'account_threshold')) {
                $table->renameColumn('current_threshold', 'account_threshold');
            }
            if (Schema::hasColumn('ad_accounts', 'current_balance') && !Schema::hasColumn('ad_accounts', 'running_ads_balance')) {
                $table->renameColumn('current_balance', 'running_ads_balance');
            }
            if (!Schema::hasColumn('ad_accounts', 'new_applied_budget')) {
                $table->decimal('new_applied_budget', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('ad_accounts', 'account_threshold')) {
                $table->renameColumn('account_threshold', 'current_threshold');
            }
            if (Schema::hasColumn('ad_accounts', 'running_ads_balance')) {
                $table->renameColumn('running_ads_balance', 'current_balance');
            }
            if (Schema::hasColumn('ad_accounts', 'new_applied_budget')) {
                $table->dropColumn('new_applied_budget');
            }
        });
    }
};
