<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Skipping creation since the table already exists.
        // This migration was previously executed manually or externally.

        // Schema::create('campaign_insights', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('campaign_id');
        //     $table->string('campaign_name')->nullable();
        //     $table->string('delivery')->nullable();
        //     $table->longText('actions')->nullable();
        //     $table->string('bid_strategy')->nullable();
        //     $table->string('budget')->nullable();
        //     $table->string('last_edit')->nullable();
        //     $table->string('attribution_setting')->nullable();
        //     $table->integer('results')->nullable();
        //     $table->integer('reach')->nullable();
        //     $table->integer('impressions')->nullable();
        //     $table->decimal('cost_per_result', 10, 2)->nullable();
        //     $table->decimal('spend', 10, 2)->nullable();
        //     $table->date('ends')->nullable();
        //     $table->string('schedule')->nullable();
        //     $table->string('duration')->nullable();
        //     $table->string('quality_rank')->nullable();
        //     $table->string('engagement_rank')->nullable();
        //     $table->string('conversion_rank')->nullable();
        //     $table->unsignedBigInteger('customer_id')->nullable();
        //     $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // Do not drop the table, it already exists and contains important data.
        // Schema::dropIfExists('campaign_insights');
    }
};