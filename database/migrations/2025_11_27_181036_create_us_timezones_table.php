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
    Schema::create('us_timezones', function (Blueprint $table) {
        $table->id();
        $table->string('state'); // New York, California, Texas
        $table->string('timezone'); // America/New_York etc
        $table->string('current_time')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('us_timezones');
    }
};
