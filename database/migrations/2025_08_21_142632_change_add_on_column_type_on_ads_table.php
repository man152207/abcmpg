<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('ads', 'add_on')) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("ALTER TABLE ads ALTER COLUMN add_on TYPE json USING add_on::json");
            } else {
                Schema::table('ads', function (Blueprint $table) {
                    $table->json('add_on')->nullable()->change();
                });
            }
        }
    }

    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            if (Schema::hasColumn('ads', 'add_on')) {
                $table->string('add_on')->nullable()->change();
            }
        });
    }
};
