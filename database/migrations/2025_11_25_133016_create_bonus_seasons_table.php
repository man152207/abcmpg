<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
public function up()
{
    Schema::create('bonus_seasons', function (Blueprint $table) {
        $table->id();
        $table->date('start_date');
        $table->date('end_date');
        $table->boolean('is_active')->default(false)->index();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('bonus_seasons');
}

};
