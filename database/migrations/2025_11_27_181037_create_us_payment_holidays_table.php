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
    Schema::create('us_payment_holidays', function (Blueprint $table) {
        $table->id();
        $table->string('provider'); // PayPal, Relay, Wise, Stripe
        $table->date('date');
        $table->string('status')->default('closed');
        $table->string('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('us_payment_holidays');
    }
};
