#!/bin/bash
set -e

echo "=== Post-merge setup starting ==="

# Install/update PHP Composer dependencies
if [ -f "composer.json" ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

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
