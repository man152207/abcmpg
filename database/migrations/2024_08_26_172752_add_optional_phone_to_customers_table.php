<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionalPhoneToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add the optional phone_2 column after the phone column
            $table->string('phone_2')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop the phone_2 column if the migration is rolled back
            $table->dropColumn('phone_2');
        });
    }
}