<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminToCustomerRequirementsTable extends Migration
{
    public function up()
    {
        Schema::table('customer_requirements', function (Blueprint $table) {
            $table->string('admin')->nullable()->after('body');
        });
    }

    public function down()
    {
        Schema::table('customer_requirements', function (Blueprint $table) {
            $table->dropColumn('admin');
        });
    }
}