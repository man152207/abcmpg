<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (DB::getDriverName() === 'pgsql') {
            // PostgreSQL handles enums differently - skip MySQL-specific ALTER
            return;
        }
        DB::statement("ALTER TABLE customer_requirements 
            MODIFY note_type ENUM('requirement', 'suggestion', 'post_caption', 'greeting', 'faq') 
            NOT NULL DEFAULT 'requirement'");
    }

    public function down()
    {
        if (DB::getDriverName() === 'pgsql') {
            return;
        }
        DB::statement("ALTER TABLE customer_requirements 
            MODIFY note_type ENUM('requirement', 'suggestion') 
            NOT NULL DEFAULT 'requirement'");
    }
};
