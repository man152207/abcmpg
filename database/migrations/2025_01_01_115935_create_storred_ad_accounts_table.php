<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorredAdAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('storred_ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('ad_account_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('storred_ad_accounts');
    }
}