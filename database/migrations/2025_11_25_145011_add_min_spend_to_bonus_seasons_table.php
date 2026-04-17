<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bonus_seasons', function (Blueprint $table) {
            if (!Schema::hasColumn('bonus_seasons', 'min_spend')) {
                $table->decimal('min_spend', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('bonus_seasons', function (Blueprint $table) {
            $table->dropColumn('min_spend');
        });
    }
};
