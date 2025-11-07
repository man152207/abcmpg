<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('balance_rejects', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('customer')->nullable();
            $table->float('USD')->nullable();
            $table->string('Remarks')->nullable();
            $table->string('xyz')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_rejects');
    }
};
