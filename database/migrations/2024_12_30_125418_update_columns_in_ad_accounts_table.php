<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsInAdAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('current_threshold', 'account_threshold');
            $table->renameColumn('current_balance', 'running_ads_balance');

            // Add new column
            $table->decimal('new_applied_budget', 10, 2)->nullable()->after('targeted_budget');
        });
    }

    public function down()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            // Revert column renames
            $table->renameColumn('account_threshold', 'current_threshold');
            $table->renameColumn('running_ads_balance', 'current_balance');

            // Drop new column
            $table->dropColumn('new_applied_budget');
        });
    }
}
