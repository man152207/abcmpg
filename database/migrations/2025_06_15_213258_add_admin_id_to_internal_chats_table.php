<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_chats', function (Blueprint $table) {
            if (Schema::hasColumn('internal_chats', 'admin_id')) {
                try {
                    $table->foreign('admin_id', 'ic_admin_fk')
                          ->references('id')->on('admins')
                          ->nullOnDelete();
                } catch (\Exception $e) {
                    // Foreign key may already exist
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('internal_chats', function (Blueprint $table) {
            try {
                $table->dropForeign('ic_admin_fk');
            } catch (\Exception $e) {}
        });
    }
};
