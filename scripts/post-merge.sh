#!/bin/bash
set -e

echo "=== Post-merge setup starting ==="

# Install/update PHP Composer dependencies
if [ -f "composer.json" ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Reset migrations sequence in case a data import overwrote the migrations table
echo "Resetting migrations sequence..."
php artisan db:query "SELECT setval('migrations_id_seq', GREATEST((SELECT COALESCE(MAX(id),0) FROM migrations) + 1, 1))" \
  --no-interaction 2>/dev/null || true

# Run any pending database migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Clear and rebuild caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimise for production-like usage
php artisan config:cache
php artisan route:cache

echo "=== Post-merge setup complete ==="
