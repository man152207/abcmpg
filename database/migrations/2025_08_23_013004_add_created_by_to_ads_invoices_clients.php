<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('ads') && !Schema::hasColumn('ads','created_by')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->index()->after('id'); // admin id
            });
        }
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices','created_by')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->index()->after('id');
            });
        }
        if (Schema::hasTable('clients') && !Schema::hasColumn('clients','created_by')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->index()->after('id');
            });
        }
    }

    public function down(): void {
        if (Schema::hasTable('ads') && Schema::hasColumn('ads','created_by')) {
            Schema::table('ads', fn(Blueprint $t) => $t->dropColumn('created_by'));
        }
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices','created_by')) {
            Schema::table('invoices', fn(Blueprint $t) => $t->dropColumn('created_by'));
        }
        if (Schema::hasTable('clients') && Schema::hasColumn('clients','created_by')) {
            Schema::table('clients', fn(Blueprint $t) => $t->dropColumn('created_by'));
        }
    }
};
