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
        Schema::create('invoice__items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id');
            $table->bigInteger('Item_id');
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('rate', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice__items');
    }
};
