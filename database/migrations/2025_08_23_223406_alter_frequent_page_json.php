<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public function up(): void {
    // --- 1) DATA CLEANUP ---

    // active_hours: NULL, empty, non-digit -> 0
DB::statement("
  UPDATE user_activities
  SET active_hours = 0
  WHERE active_hours IS NULL
     OR CAST(active_hours AS CHAR) = ''
     OR CAST(active_hours AS CHAR) NOT REGEXP '^[0-9]+$'
");

// inactive_time: NULL, empty, non-digit -> 0
DB::statement("
  UPDATE user_activities
  SET inactive_time = 0
  WHERE inactive_time IS NULL
     OR CAST(inactive_time AS CHAR) = ''
     OR CAST(inactive_time AS CHAR) NOT REGEXP '^[0-9]+$'
");


    // frequent_page: खाली => NULL
    DB::statement("
      UPDATE user_activities
      SET frequent_page = NULL
      WHERE frequent_page IS NULL
         OR TRIM(frequent_page) = ''
         OR TRIM(frequent_page) = 'NULL'
    ");

    // यदि value stringified-JSON छ (e.g. '{\"k\":1}'), unquote गरेर real JSON बनाउने
    DB::statement("
      UPDATE user_activities
      SET frequent_page = JSON_UNQUOTE(frequent_page)
      WHERE frequent_page IS NOT NULL
        AND JSON_VALID(frequent_page) = 1
        AND JSON_VALID(JSON_UNQUOTE(frequent_page)) = 1
    ");

    // बाँकी invalid हरु (JSON_VALID=0) लाई safe string JSON मा wrap (\"...\")
    DB::statement("
      UPDATE user_activities
      SET frequent_page = JSON_QUOTE(frequent_page)
      WHERE frequent_page IS NOT NULL
        AND JSON_VALID(frequent_page) = 0
    ");

    // --- 2) ALTER COLUMNS ---
    Schema::table('user_activities', function (Blueprint $table) {
      // JSON supported server: json(); unsupported: mediumText() fallback
      try {
        $table->json('frequent_page')->nullable()->change();
      } catch (\Throwable $e) {
        $table->mediumText('frequent_page')->nullable()->change();
      }

      $table->unsignedBigInteger('active_hours')->default(0)->nullable(false)->change();
      $table->unsignedBigInteger('inactive_time')->default(0)->nullable(false)->change();
    });
  }

  public function down(): void {
    Schema::table('user_activities', function (Blueprint $table) {
      $table->string('frequent_page', 255)->nullable()->change();
      $table->unsignedBigInteger('active_hours')->nullable()->default(null)->change();
      $table->unsignedBigInteger('inactive_time')->nullable()->default(null)->change();
    });
  }
};
