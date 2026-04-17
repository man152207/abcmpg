<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ads') && Schema::hasColumn('ads', 'billing_status')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('billing_status')->nullable()->default('Bill Not Sent')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ads') && Schema::hasColumn('ads', 'billing_status')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('billing_status')->nullable(false)->default('Bill Not Sent')->change();
            });
        }
    }
};
