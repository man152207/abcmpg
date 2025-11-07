<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_prompts', function (Blueprint $table) {
            $table->id();

            // कुन bot / channel को prompt हो भनेर key राख्ने
            $table->string('key')->default('facebook_messenger');

            // actual prompt
            $table->text('prompt_text');

            // active prompt हो कि होइन
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_prompts');
    }
};
