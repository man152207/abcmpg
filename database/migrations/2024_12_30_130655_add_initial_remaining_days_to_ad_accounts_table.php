<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('ad_accounts', function (Blueprint $table) {
        $table->integer('initial_remaining_days')->nullable()->after('new_applied_budget');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_accounts', function (Blueprint $table) {
            //
        });
    }
};
