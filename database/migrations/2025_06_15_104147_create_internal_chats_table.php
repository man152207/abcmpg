<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalChatsTable extends Migration
{
    public function up()
    {
        Schema::create('internal_chats', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('internal_chats');
    }
};
