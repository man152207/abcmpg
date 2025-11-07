<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecoveryCodeToAuthCodesTable extends Migration
{
    public function up()
    {
        Schema::table('auth_codes', function (Blueprint $table) {
            $table->string('recovery_code')->nullable()->after('auth_token_code');
        });
    }

    public function down()
    {
        Schema::table('auth_codes', function (Blueprint $table) {
            $table->dropColumn('recovery_code');
        });
    }
}