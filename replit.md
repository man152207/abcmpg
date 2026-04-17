# MPG Solution - Business Management System

## Project Overview

A comprehensive business management system built with Laravel 10 for MPG Solution. It includes:
- Customer portal for campaign insights, invoices, and profile management
- Admin panel with multi-role access (admins, clients, reception)
- Facebook/Meta Ads integration (Graph SDK)
- Social Media Marketing (SMMX) module with deliverables and reporting
- Reception/ERP module for student enrollments and payments
- Internal chat system with real-time features (Pusher/WebSockets)
- Nepali date support (NepaliDateConverter helper)
- 2FA management for admins

## Tech Stack

- **Backend:** PHP 8.2 + Laravel 10
- **Database:** cPanel MySQL (190.92.174.35 / mpgcomnp_wp146) — both dev and production
- **Frontend:** Blade templates + Livewire + Bootstrap 4 + jQuery
- **Real-time:** Pusher + beyondcode/laravel-websockets
- **Build:** Vite
- **PDF:** barryvdh/laravel-dompdf
- **Excel:** maatwebsite/excel
- **Images:** intervention/image

## Environment Setup (Replit)

- **Database (dev + production):** cPanel MySQL at 190.92.174.35:3306, db: mpgcomnp_wp146
  - `DB_CONNECTION=mysql` set in Replit development env vars
  - `MYSQL_DATABASE_URL` Replit Secret holds full connection string (credentials)
  - Replit egress IP `34.47.205.64` is whitelisted in cPanel → Remote MySQL
- **PostgreSQL (heliumdb):** Still available as `DB::connection('pgsql')` for migration/validation tools
  - Accessed via Replit's auto-set `DATABASE_URL` env var (no manual config needed)
- **APP_URL:** Set to Replit dev domain via development env var

## Running the Application

The workflow "Start application" runs:
```
php artisan serve --host=0.0.0.0 --port=5000
```

## Key Directories

- `app/Http/Controllers/Admin/` - Admin controllers
- `app/Http/Controllers/Reception/` - Reception/ERP controllers  
- `app/Http/Controllers/Smmx/` - SMM controllers
- `app/Http/Livewire/` - Livewire components
- `resources/views/admin/` - Admin views
- `resources/views/customer/` - Customer portal views
- `resources/views/reception/` - Reception views
- `public/plugins/` - Frontend libraries (AdminLTE/jQuery ecosystem)
- `database/migrations/` - Database schema

## Important Notes

- Many migrations had class naming conflicts (MySQL → PostgreSQL migration); fixed with anonymous classes and defensive column checks
- TrustProxies set to `*` for Replit's proxy environment
- Storage link already exists at `public/storage`

## Database Migration (Replit PostgreSQL → cPanel MySQL)

When the cPanel MySQL firewall is open and `MYSQL_DATABASE_URL` (or equivalent env vars) is set,
migrate all production data from Replit PostgreSQL to cPanel MySQL:

**Quick start:**
```bash
# 1. Verify MySQL connectivity
php scripts/verify-mysql.php

# 2. Run migrations on MySQL
DB_CONNECTION=mysql php artisan migrate --force

# 3. Copy all data from PostgreSQL to MySQL
php artisan db:pgsql-to-mysql --clear

# Or use the orchestrating shell script (all three steps):
bash scripts/migrate-to-mysql.sh
```

**Command options:**
```
php artisan db:pgsql-to-mysql
  --tables=tbl1,tbl2   # migrate specific tables only
  --clear              # truncate MySQL tables before inserting
  --dry-run            # preview row counts without writing
  --chunk=500          # rows per INSERT batch (default 200)
```

- Both connections must be reachable: `pgsql` (Replit `DATABASE_URL`) and `mysql` (`MYSQL_DATABASE_URL`)
- MySQL schema must exist first (`DB_CONNECTION=mysql php artisan migrate`)
- FOREIGN_KEY_CHECKS is disabled during import and re-enabled after each table
- Boolean/JSON/null coercion from PostgreSQL types to MySQL types is handled automatically

---

## Database Import (cPanel → Replit PostgreSQL)

Real production data has been imported from cPanel MySQL into Replit PostgreSQL.

**Import command:**
```
php artisan db:import-mysql <dump.sql> [--clear] [--dry-run] [--tables=tbl1,tbl2]
```
- Exports from cPanel phpMyAdmin (SQL format, default settings)
- Upload the .sql file to the project root (never commit it — SQL dumps are gitignored)
- Run the command; it handles MySQL→PostgreSQL syntax conversion automatically
- `--clear` truncates existing data before importing (safe to re-run)

**Current data (imported April 2026):**
- ads: 11,319 | customers: 475 | admins: 9 | cards: 34
- card_credit_info: 2,263 | card_debit_info: 1,929 | campaign_links: 858
- clients: 1,397 | crm_contacts: 82 | packages: 23
- Total: 21,494+ rows across 49 tables

**Schema differences fixed (ALTER TABLE columns added):**
- ads: customer_id, daily_budget, ad_links
- customers: city, assigned_admin_id, is_vip, last_interaction_at, last_note_at, created_by
- invoices: customer, salesperson, invoice_number, description, date
- Several other tables: see migration history

## Production Monitoring

A public health-check endpoint is available:
- **URL:** `GET /health` → returns `{"status":"ok","db_ok":true,"driver":"mysql","db_name":"..."}` (HTTP 200) or HTTP 503 on DB failure
- **Setup guide:** `docs/monitoring.md` — step-by-step instructions to wire `/health` to a free UptimeRobot monitor for automatic email/SMS alerts
