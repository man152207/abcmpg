<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prompts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('client')->nullable(); // Assigned Client
            $table->enum('department', ['Operations', 'Productions', 'Reception'])->default('Operations');
            $table->longText('body');             // prompt text
            $table->boolean('is_fav')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete(); // optional
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prompts');
    }
};