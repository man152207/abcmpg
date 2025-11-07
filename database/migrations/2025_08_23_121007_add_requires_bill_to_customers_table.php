<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('customers', function (Blueprint $table) {
        if (!Schema::hasColumn('customers', 'requires_bill')) {
            $table->boolean('requires_bill')->default(false)->after('profile_picture');
        }
    });
}
public function down(): void {
    Schema::table('customers', function (Blueprint $table) {
        if (Schema::hasColumn('customers', 'requires_bill')) {
            $table->dropColumn('requires_bill');
        }
    });
}

};
