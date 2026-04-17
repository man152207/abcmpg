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
- **Database:** PostgreSQL (Replit built-in)
- **Frontend:** Blade templates + Livewire + Bootstrap 4 + jQuery
- **Real-time:** Pusher + beyondcode/laravel-websockets
- **Build:** Vite
- **PDF:** barryvdh/laravel-dompdf
- **Excel:** maatwebsite/excel
- **Images:** intervention/image

## Environment Setup (Replit)

- **Database:** Replit PostgreSQL (heliumdb via PGHOST=helium, port 5432)
  - DB_CONNECTION=pgsql, DB_HOST=helium, DB_PORT=5432, DB_DATABASE=heliumdb, DB_USERNAME=postgres
  - DB_PASSWORD set as Replit secret
- **APP_URL:** Set to Replit dev domain via shared env var
- **Note on cPanel MySQL:** The original production database is on cPanel MySQL (190.92.174.35).
  Direct connection from Replit is NOT possible — the shared hosting provider blocks port 3306
  externally at the firewall level even with Remote MySQL Access `%` wildcard enabled.
  To use real data, export from cPanel phpMyAdmin and import into Replit PostgreSQL.

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
