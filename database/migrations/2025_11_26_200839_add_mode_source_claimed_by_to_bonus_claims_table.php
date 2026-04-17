<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bonus_claims', function (Blueprint $table) {
            if (!Schema::hasColumn('bonus_claims', 'mode')) {
                $table->string('mode', 20)->nullable();
            }
            if (!Schema::hasColumn('bonus_claims', 'source')) {
                $table->string('source', 50)->nullable();
            }
            if (!Schema::hasColumn('bonus_claims', 'claimed_by')) {
                $table->unsignedBigInteger('claimed_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('bonus_claims', function (Blueprint $table) {
            $table->dropColumn(['mode', 'source', 'claimed_by']);
        });
    }
};
