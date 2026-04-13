<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('bonus_claims')) {
        Schema::create('bonus_claims', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->string('season_code')->index();
            $table->decimal('claim_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('claimed_at')->nullable();

            $table->timestamps();
        });
    }
}


    public function down(): void
    {
        Schema::dropIfExists('bonus_claims');
    }
};
