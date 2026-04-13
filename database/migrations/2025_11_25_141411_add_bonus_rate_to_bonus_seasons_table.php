<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('bonus_seasons', function (Blueprint $table) {
        $table->decimal('bonus_rate', 5, 2)->default(1); // 1% default
    });
}

public function down()
{
    Schema::table('bonus_seasons', function (Blueprint $table) {
        $table->dropColumn('bonus_rate');
    });
}

};
