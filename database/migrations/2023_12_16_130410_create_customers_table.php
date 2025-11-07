<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->decimal('usd_rate', 8, 2)->default(160.00);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('phone_2')->nullable();
            $table->string('address');
            $table->timestamps();
            $table->string('profile_picture')->nullable();
            $table->string('facebook_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};