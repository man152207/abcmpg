<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('daily_card_spends', function (Blueprint $table) {
        $table->dropColumn('amount_npr'); // Remove the old column
        $table->string('ad_account')->after('amount_usd'); // Add the new column
    });
}

public function down()
{
    Schema::table('daily_card_spends', function (Blueprint $table) {
        $table->dropColumn('ad_account');
        $table->decimal('amount_npr', 10, 2)->after('amount_usd'); // Add the old column back
    });
}
};
