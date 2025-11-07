<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterNoteTypeEnumInCustomerRequirements extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE customer_requirements 
            MODIFY note_type ENUM('requirement', 'suggestion', 'post_caption', 'greeting', 'faq') 
            NOT NULL DEFAULT 'requirement'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE customer_requirements 
            MODIFY note_type ENUM('requirement', 'suggestion') 
            NOT NULL DEFAULT 'requirement'");
    }
}
