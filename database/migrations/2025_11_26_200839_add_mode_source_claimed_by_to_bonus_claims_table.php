<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('bonus_claims', function (Blueprint $table) {
        $table->string('mode', 20)->nullable()->after('amount_usd');
        $table->string('source', 50)->nullable()->after('mode');
        $table->unsignedBigInteger('claimed_by')->nullable()->after('source');
        // optional:
        // $table->foreign('claimed_by')->references('id')->on('admins')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('bonus_claims', function (Blueprint $table) {
        $table->dropColumn(['mode', 'source', 'claimed_by']);
    });
}

};
