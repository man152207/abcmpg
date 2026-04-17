<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('daily_card_spends', function (Blueprint $table) {
            if (Schema::hasColumn('daily_card_spends', 'amount_npr')) {
                $table->dropColumn('amount_npr');
            }
            if (!Schema::hasColumn('daily_card_spends', 'ad_account')) {
                $table->string('ad_account')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('daily_card_spends', function (Blueprint $table) {
            if (Schema::hasColumn('daily_card_spends', 'ad_account')) {
                $table->dropColumn('ad_account');
            }
            if (!Schema::hasColumn('daily_card_spends', 'amount_npr')) {
                $table->decimal('amount_npr', 10, 2)->nullable();
            }
        });
    }
};
