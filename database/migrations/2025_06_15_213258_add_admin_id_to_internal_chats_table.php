<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_chats', function (Blueprint $table) {
            
    $table->foreign('admin_id', 'ic_admin_fk')
          ->references('id')->on('admins')
          ->nullOnDelete();
});
    }

    public function down(): void
{
    Schema::table('internal_chats', function (Blueprint $table) {
        $table->dropForeign('ic_admin_fk');   // यही नाम
    });
}
};
