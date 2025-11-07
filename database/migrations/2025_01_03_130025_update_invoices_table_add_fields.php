<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->unsignedBigInteger('ad_id')->nullable()->after('customer');
        $table->decimal('amount', 10, 2)->nullable()->after('description');
        $table->string('status')->default('Generated')->after('amount');
        $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropForeign(['ad_id']);
        $table->dropColumn(['ad_id', 'amount', 'status']);
    });
}

};
