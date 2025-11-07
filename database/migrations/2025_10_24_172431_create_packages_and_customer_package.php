<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // packages table
        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $t) {
                $t->engine = 'InnoDB';

                $t->id();
                $t->string('external_id')->unique();   // Supabase UUID
                $t->string('code')->nullable()->index();
                $t->string('name');
                $t->decimal('price', 12, 2)->default(0);
                $t->string('currency', 8)->default('NPR');
                $t->json('features')->nullable();
                $t->boolean('active')->default(true);
                $t->boolean('is_popular')->default(false);
                $t->timestamp('synced_at')->nullable();
                $t->timestamps();
            });
        }

        // customer_package pivot (FK हटाएर index मात्र)
        if (!Schema::hasTable('customer_package')) {
            Schema::create('customer_package', function (Blueprint $t) {
                $t->engine = 'InnoDB';

                $t->id();
                $t->unsignedBigInteger('customer_id')->index();
                $t->unsignedBigInteger('package_id')->index();
                $t->timestamp('assigned_at')->nullable();
                $t->unique(['customer_id','package_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customer_package')) {
            Schema::drop('customer_package');
        }
        if (Schema::hasTable('packages')) {
            Schema::drop('packages');
        }
    }
};
