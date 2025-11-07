<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->text('service_details');
            $table->string('campaign_objectives')->nullable();
            $table->string('budget')->nullable();
            $table->string('duration')->nullable();
            $table->string('target_location')->nullable();
            $table->string('age_range');
            $table->string('gender');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
