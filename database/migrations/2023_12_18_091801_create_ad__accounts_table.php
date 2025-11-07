<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdAccountsTable extends Migration
{
    public function up()
{
    Schema::table('ad__accounts', function (Blueprint $table) {
        $table->string('ad_account_name')->after('id')->nullable();
        $table->decimal('current_threshold', 10, 2)->default(0);
        $table->decimal('current_balance', 10, 2)->default(0);
        $table->decimal('targeted_budget', 10, 2)->default(0);
    });
}

public function down()
{
    Schema::table('ad__accounts', function (Blueprint $table) {
        $table->dropColumn(['ad_account_name', 'current_threshold', 'current_balance', 'targeted_budget']);
    });
}

}