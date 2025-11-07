<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('ads', function (Blueprint $table) {
        $table->string('add_on')->nullable()->after('Ad_Nature_Page');
        // यदि boolean flag मात्र चाहिन्छ भने:
        // $table->boolean('add_on')->default(false)->after('Ad_Nature_Page');
        // यदि बहु-विकल्प/डेटा लिस्ट स्टोर गर्नुपर्‍यो भने:
        // $table->json('add_on')->nullable()->after('Ad_Nature_Page');
    });
}

public function down()
{
    Schema::table('ads', function (Blueprint $table) {
        $table->dropColumn('add_on');
    });
}

};
