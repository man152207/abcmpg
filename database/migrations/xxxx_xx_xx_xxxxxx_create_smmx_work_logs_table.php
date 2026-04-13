<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smmx_work_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('onboarding_id')->nullable();
            $table->unsignedBigInteger('deliverable_id')->nullable();

            $table->date('work_date');
            $table->unsignedTinyInteger('report_month');
            $table->unsignedSmallInteger('report_year');

            $table->string('work_type')->nullable(); // post, graphic, reel, story, ad_campaign, report, approval, meeting, other
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);

            $table->string('status')->default('pending'); // pending, in_progress, done, waiting_approval
            $table->string('assigned_to')->nullable();

            $table->string('asset_link')->nullable();
            $table->string('external_link')->nullable();
            $table->text('remark')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'report_month', 'report_year']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smmx_work_logs');
    }
};