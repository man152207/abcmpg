<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdAccountFbadminTable extends Migration
{
    public function up()
{
    Schema::create('ad_account_fbadmin', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ad_account_id')->constrained('ad_accounts')->onDelete('cascade');
        $table->foreignId('fbadmin_id')->constrained('fbadmins')->onDelete('cascade');
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('ad_account_fbadmin');
    }
}
