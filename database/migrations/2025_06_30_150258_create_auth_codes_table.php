<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthCodesTable extends Migration
{
    public function up()
    {
        Schema::create('auth_codes', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('auth_token_code');
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auth_codes');
    }
}