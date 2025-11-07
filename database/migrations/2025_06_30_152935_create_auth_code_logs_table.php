<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthCodeLogsTable extends Migration
{
    public function up()
    {
        Schema::create('auth_code_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auth_code_id');
            $table->unsignedBigInteger('admin_id');
            $table->string('device')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->foreign('auth_code_id')->references('id')->on('auth_codes')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('auth_code_logs');
    }
}
