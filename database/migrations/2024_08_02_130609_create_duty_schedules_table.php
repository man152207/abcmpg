<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDutySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('duty_schedules')) {
            Schema::create('duty_schedules', function (Blueprint $table) {
                $table->id();

                $table->json('operations_on')->nullable();
                $table->json('operations_off')->nullable();
                $table->json('covers')->nullable();
                $table->text('remarks')->nullable();
                $table->string('production')->nullable();
                $table->string('reception')->nullable();
                $table->string('helper')->nullable();

                $table->timestamps(); // created_at / updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duty_schedules');
    }
}
