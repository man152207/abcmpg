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
    Schema::create('us_holidays', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->date('date');
        $table->string('type')->nullable();    // federal, bank, state
        $table->string('source')->nullable();  // nager.at, fed.gov, manual
        $table->string('state')->nullable();   // NY, CA, TX
        $table->boolean('bank_closed')->default(false);
        $table->boolean('payment_closed')->default(false);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('us_holidays');
    }
};
