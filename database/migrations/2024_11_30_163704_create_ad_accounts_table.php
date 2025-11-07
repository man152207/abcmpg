<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdAccountsTable extends Migration
{
    public function up()
{
    Schema::create('ad_accounts', function (Blueprint $table) {
        $table->id();
        $table->string('account_name');
        $table->date('active_since')->nullable();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('ad_accounts');
    }
}
