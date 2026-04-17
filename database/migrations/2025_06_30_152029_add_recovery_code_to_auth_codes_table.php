<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('auth_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('auth_codes', 'recovery_code')) {
                $table->string('recovery_code')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('auth_codes', function (Blueprint $table) {
            $table->dropColumn('recovery_code');
        });
    }
};
