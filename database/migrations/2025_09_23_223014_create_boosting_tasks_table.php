<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('boosting_tasks', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable(); // admins.id
            $table->unsignedBigInteger('dispatcher_id')->nullable(); // जसले entry गर्यो
            
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamp('requested_time')->nullable();
            $table->timestamp('eta_time')->nullable();
            $table->enum('status', ['Pending','In Progress','Done'])->default('Pending');
            $table->string('priority')->default('Normal'); // Normal/Urgent
            $table->text('remarks')->nullable();
            $table->timestamp('completed_time')->nullable();
            
            $table->timestamps();


            // Indexes for speed (since we're not enforcing FK at DB level here)
            $table->index('customer_id');
            $table->index('assigned_to');

            /*
             * If you want REAL foreign keys later:
             *   1) Confirm the datatype of customers.id and admins.id
             *      - If BIGINT UNSIGNED: keep columns as unsignedBigInteger()
             *        and then uncomment these two lines:
             *
             *        $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete()->cascadeOnUpdate();
             *        $table->foreign('assigned_to')->references('id')->on('admins')->nullOnDelete()->cascadeOnUpdate();
             *
             *      - If INT UNSIGNED (older projects): change the two columns above to unsignedInteger()
             *        and then uncomment the same FK lines.
             */
        });
    }

    public function down(): void {
        Schema::dropIfExists('boosting_tasks');
    }
};
