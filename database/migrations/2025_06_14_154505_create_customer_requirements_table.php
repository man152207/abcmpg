<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeNoteTypeToStringInCustomerRequirementsTable extends Migration
{
    public function up()
    {
        Schema::table('customer_requirements', function (Blueprint $table) {
            $table->string('note_type', 50)->default('requirement')->change();
        });
    }

    public function down()
    {
        Schema::table('customer_requirements', function (Blueprint $table) {
            $table->enum('note_type', ['requirement','suggestion'])
                  ->default('requirement')
                  ->change();
        });
    }
}
