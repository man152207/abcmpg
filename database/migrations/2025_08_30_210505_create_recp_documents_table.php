<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('recp_documents', function (Blueprint $table) {
      $table->id();
      $table->foreignId('student_id')->constrained('recp_students')->cascadeOnDelete();
      $table->string('doc_type'); // Recommendation / ID Copy / Form Fill ...
      $table->string('doc_no')->nullable();
      $table->date('issued_at')->nullable();
      $table->decimal('fee', 10, 2)->default(0);
      $table->foreignId('handled_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
      $table->text('remarks')->nullable();
      $table->timestamps();
      $table->index(['doc_type','issued_at'], 'recp_documents_type_issued_idx');
    });
  }
  public function down(): void { Schema::dropIfExists('recp_documents'); }
};
