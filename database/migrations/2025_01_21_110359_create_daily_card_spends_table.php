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
    Schema::create('daily_card_spends', function (Blueprint $table) {
        $table->id();
        $table->string('card_name');
        $table->date('date');
        $table->decimal('amount_usd', 10, 2);
        $table->decimal('amount_npr', 10, 2);
        $table->string('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_card_spends');
    }
};
