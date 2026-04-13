<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smmx_deliverables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('onboarding_id')->nullable();

            $table->unsignedTinyInteger('report_month');
            $table->unsignedSmallInteger('report_year');

            $table->integer('posts_planned')->default(0);
            $table->integer('posts_completed')->default(0);
            $table->integer('graphics_planned')->default(0);
            $table->integer('graphics_completed')->default(0);
            $table->integer('reels_planned')->default(0);
            $table->integer('reels_completed')->default(0);
            $table->integer('stories_planned')->default(0);
            $table->integer('stories_completed')->default(0);

            $table->decimal('ad_spend_planned', 12, 2)->nullable();
            $table->decimal('ad_spend_used', 12, 2)->nullable();
            $table->string('campaign_objective')->nullable();

            $table->string('approval_status')->nullable();
            $table->json('assigned_staff')->nullable();
            $table->date('planned_date')->nullable();
            $table->date('published_date')->nullable();
            $table->json('asset_links')->nullable();

            $table->text('pending_items')->nullable();
            $table->text('next_action')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('report_sent')->default(false);
            $table->string('status')->default('pending');

            $table->timestamps();

            $table->index(['customer_id', 'report_month', 'report_year']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smmx_deliverables');
    }
};