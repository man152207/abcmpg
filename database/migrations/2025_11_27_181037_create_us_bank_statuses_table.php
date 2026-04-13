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
    Schema::create('us_bank_statuses', function (Blueprint $table) {
        $table->id();
        $table->string('provider'); // Federal Reserve, JP Morgan, BoA etc
        $table->date('date');
        $table->string('status'); // open, closed, partial
        $table->string('reason')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('us_bank_statuses');
    }
};
