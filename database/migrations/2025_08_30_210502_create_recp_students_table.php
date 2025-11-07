<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('recp_students', function (Blueprint $table) {
      $table->id();
      $table->string('full_name');
      $table->string('phone')->index();
      $table->string('email')->nullable();
      $table->string('guardian_name')->nullable();
      $table->string('address')->nullable();
      $table->date('dob')->nullable();
      $table->string('photo_path')->nullable();
      $table->string('status')->default('active'); // active|inactive|completed|dropped
      $table->text('remarks')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('recp_students'); }
};
