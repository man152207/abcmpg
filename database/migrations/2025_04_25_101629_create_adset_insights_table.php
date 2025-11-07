<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('adset_insights', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id');
            $table->string('adset_name')->nullable();
            $table->string('delivery')->nullable();
            $table->longText('actions')->nullable();
            $table->string('bid_strategy')->nullable();
            $table->string('budget')->nullable();
            $table->string('last_edit')->nullable();
            $table->string('attribution_setting')->nullable();
            $table->integer('results')->nullable();
            $table->integer('reach')->nullable();
            $table->integer('impressions')->nullable();
            $table->decimal('cost_per_result', 10, 2)->nullable();
            $table->decimal('spend', 10, 2)->nullable();
            $table->date('ends')->nullable();
            $table->string('schedule')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('adset_insights');
    }
};