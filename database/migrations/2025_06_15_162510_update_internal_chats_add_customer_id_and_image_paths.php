<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_chats', function (Blueprint $table) {
            // Add image_paths only if it doesn't already exist
            if (!Schema::hasColumn('internal_chats', 'image_paths')) {
                $table->json('image_paths')->nullable()->after('message');
            }

            // Add customer_id only if it doesn't already exist
            if (!Schema::hasColumn('internal_chats', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('image_paths');
                $table->foreign('customer_id')
                      ->references('id')->on('customers')
                      ->onDelete('set null');
            }
        });

        // Migrate old single image_path -> image_paths JSON array
        if (Schema::hasColumn('internal_chats', 'image_path') && Schema::hasColumn('internal_chats', 'image_paths')) {
            DB::statement("
                UPDATE internal_chats
                SET image_paths = JSON_ARRAY(image_path)
                WHERE image_path IS NOT NULL
            ");

            // Drop only if still present
            Schema::table('internal_chats', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }

    public function down(): void
    {
        Schema::table('internal_chats', function (Blueprint $table) {
            if (!Schema::hasColumn('internal_chats', 'image_path')) {
                $table->string('image_path')->nullable()->after('message');
            }

            if (Schema::hasColumn('internal_chats', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }

            if (Schema::hasColumn('internal_chats', 'image_paths')) {
                $table->dropColumn('image_paths');
            }
        });
    }
};