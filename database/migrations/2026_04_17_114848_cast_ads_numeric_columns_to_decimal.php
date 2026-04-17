<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ads') || !Schema::hasColumn('ads', 'advance')) {
            return;
        }

        // Set empty strings to NULL before type conversion
        DB::statement("UPDATE ads SET advance = NULL WHERE advance = '' OR advance IS NULL");

        // Cast varchar → numeric (USING clause handles the conversion)
        DB::statement('ALTER TABLE ads ALTER COLUMN advance TYPE numeric(15,2) USING advance::numeric');
    }

    public function down(): void
    {
        if (!Schema::hasTable('ads') || !Schema::hasColumn('ads', 'advance')) {
            return;
        }

        DB::statement('ALTER TABLE ads ALTER COLUMN advance TYPE character varying(255) USING advance::text');
    }
};
