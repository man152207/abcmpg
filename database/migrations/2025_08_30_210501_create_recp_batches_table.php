<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('recp_batches', function (Blueprint $table) {
      $table->id();
      $table->foreignId('course_id')->constrained('recp_courses')->cascadeOnDelete();
      $table->string('name'); // e.g. "Sep-2025 A"
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->unsignedInteger('capacity')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->unique(['course_id','name'], 'recp_batches_course_name_unique');
    });
  }
  public function down(): void { Schema::dropIfExists('recp_batches'); }
};
