<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('customer_requirements')) {
            Schema::create('customer_requirements', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
        Schema::table('customer_requirements', function (Blueprint $table) {
            if (Schema::hasColumn('customer_requirements', 'note_type')) {
                $table->string('note_type', 50)->default('requirement')->change();
            }
        });
    }

    public function down()
    {
        // no-op
    }
};
