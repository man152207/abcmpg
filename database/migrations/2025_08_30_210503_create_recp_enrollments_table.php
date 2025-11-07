<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('recp_enrollments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('student_id')->constrained('recp_students')->cascadeOnDelete();
      $table->foreignId('batch_id')->constrained('recp_batches')->cascadeOnDelete();
      $table->date('enroll_date')->nullable();
      $table->decimal('fee_agreed', 10, 2)->default(0);
      $table->decimal('discount', 10, 2)->default(0);
      $table->string('status')->default('enrolled'); // enrolled|completed|dropped
      $table->timestamps();
      $table->unique(['student_id','batch_id'], 'recp_enrollments_student_batch_unique');
    });
  }
  public function down(): void { Schema::dropIfExists('recp_enrollments'); }
};
