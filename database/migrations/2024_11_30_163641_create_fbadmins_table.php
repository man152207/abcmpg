<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbadminsTable extends Migration
{
    public function up()
{
    Schema::create('fbadmins', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->date('active_since')->nullable();
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('fbadmins');
    }
}
