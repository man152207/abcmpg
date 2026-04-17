<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customer_requirements', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_requirements', 'admin')) {
                $table->string('admin')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('customer_requirements', function (Blueprint $table) {
            $table->dropColumn('admin');
        });
    }
};
