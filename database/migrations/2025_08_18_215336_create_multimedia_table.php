<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('multimedia', function (Blueprint $t) {
            $t->id();
            // Core
            $t->date('date');
            $t->string('whatsapp', 30)->nullable();
            $t->string('customer_name');
            $t->string('project')->index();
            $t->enum('status', ['pending','in_progress','completed','on_hold'])->default('pending');
            $t->string('project_by')->nullable();
            $t->enum('project_type', ['Graphics','Video']);
            $t->text('notes')->nullable();

            // Cloud/link workflow (lightweight hosting)
            $t->string('asset_link')->nullable()->index();
            $t->enum('asset_provider', ['Drive','Dropbox','OneDrive','YouTube','Vimeo','Other'])->default('Drive');
            $t->enum('asset_access', ['view_only','comment','edit'])->default('view_only');
            $t->enum('asset_type', ['Image','Video','PSD/AI','Doc','Other'])->default('Other');
            $t->string('asset_version', 20)->nullable();
            $t->decimal('asset_size_mb', 8,2)->nullable();

            // Workflow & tracking
            $t->unsignedBigInteger('client_id')->nullable()->index();
            $t->unsignedBigInteger('assigned_to')->nullable()->index();
            $t->enum('priority',['low','normal','high','urgent'])->default('normal');
            $t->date('due_date')->nullable();
            $t->json('platforms')->nullable();          // ["Facebook","Instagram"]
            $t->string('caption_link')->nullable();      // Google Doc for captions/copies
            $t->string('publish_url')->nullable();       // Published post URL
            $t->unsignedSmallInteger('revision_count')->default(0);
            $t->boolean('approved_by_client')->default(false);
            $t->boolean('qa_checked')->default(false);
            $t->string('billing_code')->nullable();
            $t->decimal('estimate_hours',6,2)->nullable();
            $t->decimal('actual_hours',6,2)->nullable();
            $t->decimal('cost_npr',12,2)->nullable();

            // Audit
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();

            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multimedia');
    }
};
