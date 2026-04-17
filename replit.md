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

## Environment Setup

- **Database:** Replit PostgreSQL (heliumdb via PGHOST=helium)
- **DB_PASSWORD:** Set as Replit secret
- **APP_URL:** Set to Replit dev domain

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
