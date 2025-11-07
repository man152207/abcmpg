<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // NOTE: JSON लाई change गर्न doctrine/dbal चाहिन्छ
        // composer require doctrine/dbal
        Schema::table('ads', function (Blueprint $table) {
            // DB ले सपोर्ट गर्छ भने JSON, नभए text() प्रयोग गर्नुस् (तल NOTE हेर्नुहोस्)
            $table->json('add_on')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('add_on')->nullable()->change();
        });
    }
};
