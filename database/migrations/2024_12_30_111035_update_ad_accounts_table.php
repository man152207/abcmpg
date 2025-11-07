<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('ad_accounts', function (Blueprint $table) {
        if (!Schema::hasColumn('ad_accounts', 'account_name')) {
            $table->string('account_name')->nullable(); // Add missing column
        }
        if (!Schema::hasColumn('ad_accounts', 'current_threshold')) {
            $table->decimal('current_threshold', 10, 2)->nullable(); // Add threshold
        }
        if (!Schema::hasColumn('ad_accounts', 'current_balance')) {
            $table->decimal('current_balance', 10, 2)->nullable(); // Add balance
        }
        if (!Schema::hasColumn('ad_accounts', 'targeted_budget')) {
            $table->decimal('targeted_budget', 10, 2)->nullable(); // Add budget
        }
    });
}

public function down()
{
    Schema::table('ad_accounts', function (Blueprint $table) {
        $table->dropColumn(['account_name', 'current_threshold', 'current_balance', 'targeted_budget']);
    });
}
};
