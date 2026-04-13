<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smmx_onboardings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('package_id')->nullable();

            $table->string('business_name');
            $table->string('brand_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('business_address')->nullable();

            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('tiktok_link')->nullable();
            $table->string('website_link')->nullable();

            $table->string('page_access_status')->nullable();
            $table->string('business_manager_status')->nullable();

            $table->string('primary_goal')->nullable();
            $table->string('target_location')->nullable();
            $table->string('target_age_group')->nullable();
            $table->string('target_gender')->nullable();
            $table->text('target_interests')->nullable();
            $table->text('competitors')->nullable();

            $table->string('brand_colors')->nullable();
            $table->string('preferred_language')->nullable();
            $table->text('content_preferences')->nullable();
            $table->string('monthly_budget')->nullable();

            $table->boolean('approval_required')->default(false);
            $table->string('approval_contact')->nullable();

            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index('customer_id');
            $table->index('package_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smmx_onboardings');
    }
};