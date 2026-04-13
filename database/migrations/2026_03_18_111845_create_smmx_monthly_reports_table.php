<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smmx_monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('deliverable_id');

            $table->unsignedTinyInteger('report_month');
            $table->unsignedSmallInteger('report_year');

            $table->bigInteger('total_reach')->nullable();
            $table->bigInteger('total_impressions')->nullable();
            $table->integer('total_leads')->nullable();
            $table->integer('total_messages')->nullable();
            $table->decimal('total_spend', 12, 2)->nullable();

            $table->decimal('completion_rate', 8, 2)->nullable();
            $table->string('best_performing_content')->nullable();
            $table->text('summary_remark')->nullable();

            $table->string('report_status')->default('draft');
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'report_month', 'report_year']);
            $table->index('deliverable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smmx_monthly_reports');
    }
};