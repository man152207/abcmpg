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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->decimal('USD', 10, 2);
            $table->decimal('Rate', 10, 2);
            $table->decimal('NRP', 10, 2);
            $table->string('Ad_Account');
            $table->string('Payment');
            $table->integer('Duration');
            $table->integer('Quantity');
            $table->string('Status');
            $table->string('Ad_Nature_Page');
            $table->string('advance')->nullable();
            $table->string('admin');
            $table->integer('is_complete');
            $table->timestamps();

            // Define foreign key constraints
            // $table->foreign('customer')->references('id')->on('users');
            // $table->foreign('admin')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
