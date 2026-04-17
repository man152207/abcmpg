<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ads: add customer_id, daily_budget, ad_links
        Schema::table('ads', function (Blueprint $table) {
            if (!Schema::hasColumn('ads', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('ads', 'daily_budget')) {
                $table->decimal('daily_budget', 10, 2)->nullable()->after('Duration');
            }
            if (!Schema::hasColumn('ads', 'ad_links')) {
                $table->text('ad_links')->nullable()->after('advance');
            }
        });

        // customers: add city, assigned_admin_id, is_vip, last_interaction_at, last_note_at, created_by
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('customers', 'assigned_admin_id')) {
                $table->unsignedBigInteger('assigned_admin_id')->nullable()->after('city');
            }
            if (!Schema::hasColumn('customers', 'is_vip')) {
                $table->boolean('is_vip')->default(false)->after('assigned_admin_id');
            }
            if (!Schema::hasColumn('customers', 'last_interaction_at')) {
                $table->timestamp('last_interaction_at')->nullable()->after('is_vip');
            }
            if (!Schema::hasColumn('customers', 'last_note_at')) {
                $table->timestamp('last_note_at')->nullable()->after('last_interaction_at');
            }
            if (!Schema::hasColumn('customers', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('last_note_at');
            }
        });

        // invoices: add customer, salesperson, invoice_number, description, date
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'customer')) {
                $table->string('customer')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('invoices', 'salesperson')) {
                $table->string('salesperson')->nullable()->after('customer');
            }
            if (!Schema::hasColumn('invoices', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('salesperson');
            }
            if (!Schema::hasColumn('invoices', 'description')) {
                $table->text('description')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('invoices', 'date')) {
                $table->date('date')->nullable()->after('description');
            }
        });

        // ad_accounts: add Daily_Budget
        Schema::table('ad_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_accounts', 'Daily_Budget')) {
                $table->decimal('Daily_Budget', 10, 2)->nullable();
            }
        });

        // bonus_claims: add bonus_season_id; handle amount_usd vs claim_amount
        Schema::table('bonus_claims', function (Blueprint $table) {
            if (!Schema::hasColumn('bonus_claims', 'bonus_season_id')) {
                $table->unsignedBigInteger('bonus_season_id')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('bonus_claims', 'amount_usd') && Schema::hasColumn('bonus_claims', 'claim_amount')) {
                $table->renameColumn('claim_amount', 'amount_usd');
            } elseif (!Schema::hasColumn('bonus_claims', 'amount_usd')) {
                $table->decimal('amount_usd', 10, 2)->default(0)->after('season_code');
            }
        });

        // customer_package: add start_date, end_date, status, timestamps
        Schema::table('customer_package', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_package', 'start_date')) {
                $table->date('start_date')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('customer_package', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('customer_package', 'status')) {
                $table->string('status')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('customer_package', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('customer_package', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        // customer_requirements: add customer_id, note_type, priority, body
        Schema::table('customer_requirements', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_requirements', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('customer_requirements', 'note_type')) {
                $table->string('note_type')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('customer_requirements', 'priority')) {
                $table->string('priority')->nullable()->after('note_type');
            }
            if (!Schema::hasColumn('customer_requirements', 'body')) {
                $table->text('body')->nullable()->after('priority');
            }
        });

        // internal_chats: add image_path (singular), admin_id
        Schema::table('internal_chats', function (Blueprint $table) {
            if (!Schema::hasColumn('internal_chats', 'image_path')) {
                $table->string('image_path')->nullable()->after('message');
            }
            if (!Schema::hasColumn('internal_chats', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable()->after('customer_id');
            }
        });

        // other_incomes: add amount
        Schema::table('other_incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('other_incomes', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0)->after('customer_name');
            }
        });

        // user_activities: add all missing columns from the MySQL dump
        Schema::table('user_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('user_activities', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('user_activities', 'login_time')) {
                $table->timestamp('login_time')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('user_activities', 'active_hours')) {
                $table->decimal('active_hours', 8, 2)->nullable()->after('login_time');
            }
            if (!Schema::hasColumn('user_activities', 'location')) {
                $table->string('location')->nullable()->after('active_hours');
            }
            if (!Schema::hasColumn('user_activities', 'refresh_rate')) {
                $table->integer('refresh_rate')->nullable()->after('location');
            }
            if (!Schema::hasColumn('user_activities', 'frequent_page')) {
                $table->string('frequent_page')->nullable()->after('refresh_rate');
            }
            if (!Schema::hasColumn('user_activities', 'inactive_time')) {
                $table->decimal('inactive_time', 8, 2)->nullable()->after('frequent_page');
            }
            if (!Schema::hasColumn('user_activities', 'daily_data_entries')) {
                $table->integer('daily_data_entries')->nullable()->after('inactive_time');
            }
        });
    }

    public function down(): void
    {
        // Intentionally a no-op.
        //
        // This migration aligns the PostgreSQL schema with the MySQL source to enable
        // the one-time data import. Rolling it back is not meaningful without also
        // re-running the full import, and several of the columns checked in up() may have
        // been introduced by earlier migrations (e.g. internal_chats.admin_id,
        // user_activities.frequent_page) — dropping them here would cause schema regression.
        //
        // To fully undo this import, restore from a database backup instead.
    }
};
