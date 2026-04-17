<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ad__accounts')) {
            Schema::create('ad__accounts', function (Blueprint $table) {
                $table->id();
                $table->string('ad_account_name')->nullable();
                $table->decimal('current_threshold', 10, 2)->default(0);
                $table->decimal('current_balance', 10, 2)->default(0);
                $table->decimal('targeted_budget', 10, 2)->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('ad__accounts', function (Blueprint $table) {
                if (!Schema::hasColumn('ad__accounts', 'ad_account_name')) {
                    $table->string('ad_account_name')->nullable();
                }
                if (!Schema::hasColumn('ad__accounts', 'current_threshold')) {
                    $table->decimal('current_threshold', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('ad__accounts', 'current_balance')) {
                    $table->decimal('current_balance', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('ad__accounts', 'targeted_budget')) {
                    $table->decimal('targeted_budget', 10, 2)->default(0);
                }
            });
        }
    }

    public function down()
    {
        Schema::table('ad__accounts', function (Blueprint $table) {
            $table->dropColumn(['ad_account_name', 'current_threshold', 'current_balance', 'targeted_budget']);
        });
    }
};
