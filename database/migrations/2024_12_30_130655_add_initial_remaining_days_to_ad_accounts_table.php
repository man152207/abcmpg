<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_accounts', 'initial_remaining_days')) {
                $table->integer('initial_remaining_days')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            $table->dropColumn('initial_remaining_days');
        });
    }
};
