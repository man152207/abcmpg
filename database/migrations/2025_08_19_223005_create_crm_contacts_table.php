<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone_primary', 30)->index();
            $table->string('phone_alt', 30)->nullable();
            $table->boolean('whatsapp_opt_in')->default(false);
            $table->string('fb_profile_url')->nullable();
            $table->string('messenger_username')->nullable();

            $table->string('service_interest')->nullable();
            $table->string('budget_range')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('preferred_language', 30)->nullable()->default('Nepali');
            $table->string('source', 60)->nullable(); // Facebook/Instagram/Walk-in/Referral
            $table->string('tags')->nullable();       // comma-separated

            // Pipeline
            $table->enum('status', ['New','Warm','Follow-up Due','Negotiation','Won','Lost','Dormant'])->default('New')->index();
            $table->enum('priority', ['High','Medium','Low'])->default('Medium')->index();
            $table->unsignedBigInteger('assigned_to')->nullable()->index(); // admins.id

            $table->timestamp('last_contact_at')->nullable()->index();
            $table->timestamp('next_followup_at')->nullable()->index();

            $table->string('notes_summary', 500)->nullable();
            $table->text('consent_notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->unique(['phone_primary']); // avoid duplicates by primary phone
        });
    }

    public function down(): void {
        Schema::dropIfExists('crm_contacts');
    }
};
