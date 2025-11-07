<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Keep it unsigned
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('receipts');
    }
};

