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
    Schema::create('us_emergency_closures', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->string('state'); // NY, CA, TX
        $table->string('reason');
        $table->string('severity')->nullable(); // advisory, warning, shutdown
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('us_emergency_closures');
    }
};
