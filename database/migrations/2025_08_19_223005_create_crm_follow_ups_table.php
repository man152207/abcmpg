<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('crm_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_contact_id')->constrained('crm_contacts')->cascadeOnDelete();

            $table->enum('contact_channel', ['WhatsApp','Messenger','Call','SMS'])->index();
            $table->timestamp('planned_at')->nullable()->index();
            $table->timestamp('done_at')->nullable()->index();

            $table->enum('outcome', ['No Answer','Interested','Not Now','Converted','Invalid','Other'])->nullable()->index();
            $table->text('note')->nullable();

            $table->boolean('reminder_set')->default(false);
            $table->timestamp('snooze_until')->nullable()->index();

            $table->unsignedBigInteger('created_by')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('crm_follow_ups');
    }
};
