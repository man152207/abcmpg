<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bonus_seasons', function (Blueprint $table) {
            $table->unsignedInteger('claim_days')
                  ->default(7)
                  ->after('min_spend'); // min_spend पछि राखेको
        });
    }

    public function down(): void
    {
        Schema::table('bonus_seasons', function (Blueprint $table) {
            $table->dropColumn('claim_days');
        });
    }
};
