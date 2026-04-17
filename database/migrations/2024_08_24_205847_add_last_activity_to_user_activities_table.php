<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
        Schema::table('user_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('user_activities', 'last_activity')) {
                $table->timestamp('last_activity')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropColumn('last_activity');
        });
    }
};
