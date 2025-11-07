<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->string('type')->nullable(); // e.g. like, love, etc.
            $table->unsignedBigInteger('user_id')->nullable(); // optional: who reacted
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('internal_chats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
