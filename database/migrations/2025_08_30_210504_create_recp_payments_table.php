<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('recp_payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('enrollment_id')->constrained('recp_enrollments')->cascadeOnDelete();
      $table->decimal('amount', 10, 2);
      $table->string('method')->default('cash'); // cash|esewa|khalti|bank|card|other
      $table->string('source_account')->nullable(); // eSewa/Khalti no, Bank name
      $table->string('reference')->nullable();     // voucher/txn id
      $table->timestamp('paid_at')->nullable();
      $table->foreignId('received_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
      $table->text('note')->nullable();
      $table->timestamps();
      $table->index(['method','paid_at'], 'recp_payments_method_paid_idx');
    });
  }
  public function down(): void { Schema::dropIfExists('recp_payments'); }
};
