<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');     // कसले submit गर्‍यो
            $table->date('log_date');                   // कुन मितिको log
            $table->longText('production')->nullable(); // JSON string (rows)
            $table->longText('reception')->nullable();  // JSON string (rows)
            $table->longText('operations')->nullable(); // JSON string (rows)
            $table->text('summary')->nullable();        // end-of-day brief
            $table->enum('status', ['draft','submitted','approved'])->default('submitted');
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
            $table->unique(['admin_id','log_date']); // एउटै दिन दोहोरिन नदिने
        });
    }

    public function down(): void {
        Schema::dropIfExists('daily_logs');
    }
};
