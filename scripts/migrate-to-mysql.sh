#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────────────────
# migrate-to-mysql.sh
#
# Full migration workflow: Replit PostgreSQL → cPanel MySQL (mpgcomnp_wp146)
#
# Prerequisites:
#   1. The cPanel MySQL firewall must be open (port 3306 reachable from Replit).
#   2. The following environment variables must be set:
#        MYSQL_DATABASE_URL  — full connection string, e.g.
#                               mysql://user:pass@host:3306/dbname
#        -- OR --
#        DB_HOST             — e.g. 190.92.174.35
#        DB_DATABASE         — e.g. mpgcomnp_wp146
#        DB_USERNAME         — e.g. mpgcomnp_wp146
#        DB_MYSQL_PASSWORD   — the MySQL user password
#   3. DATABASE_URL must still point to Replit's PostgreSQL (pgsql source).
#
# Usage:
#   bash scripts/migrate-to-mysql.sh
#
# Options (set as env vars before running):
#   TABLES=users,admins   — migrate only specific tables
#   CLEAR=1               — truncate MySQL tables before inserting
#   CHUNK=500             — rows per INSERT batch (default 200)
#   DRY_RUN=1             — preview without writing
# ─────────────────────────────────────────────────────────────────────────────

set -euo pipefail

ARTISAN="php artisan"

echo "════════════════════════════════════════════════════════════════"
echo "  Replit PostgreSQL → cPanel MySQL Migration"
echo "════════════════════════════════════════════════════════════════"
echo ""

# ── Step 1: Verify MySQL connectivity ────────────────────────────────────────
echo "▶ Step 1: Verify MySQL connection"
php scripts/verify-mysql.php
echo ""

# ── Step 2: Run Laravel migrations on MySQL ──────────────────────────────────
echo "▶ Step 2: Run migrations against MySQL (DB_CONNECTION=mysql)"
DB_CONNECTION=mysql $ARTISAN migrate --force
echo ""

# ── Step 3: Migrate data from PostgreSQL to MySQL ────────────────────────────
echo "▶ Step 3: Copy data from PostgreSQL to MySQL"

CMD="$ARTISAN db:pgsql-to-mysql"

if [[ -n "${TABLES:-}" ]]; then
    CMD="$CMD --tables=${TABLES}"
fi

if [[ "${CLEAR:-0}" == "1" ]]; then
    CMD="$CMD --clear"
fi

if [[ -n "${CHUNK:-}" ]]; then
    CMD="$CMD --chunk=${CHUNK}"
fi

if [[ "${DRY_RUN:-0}" == "1" ]]; then
    CMD="$CMD --dry-run"
fi

echo "  Running: $CMD"
$CMD
echo ""

echo "════════════════════════════════════════════════════════════════"
echo "  Migration finished."
echo "  To verify the production app, visit: https://abcmpg.replit.app"
echo "════════════════════════════════════════════════════════════════"
