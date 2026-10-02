#!/bin/sh
set -e

# Create required directories and SQLite database
mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
touch database/database.sqlite
chown -R www-data:www-data database storage bootstrap/cache

# Render sets PORT env var; default to 80 if not set
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Run package:discover now that env vars are available (skipped during build with --no-scripts)
php artisan package:discover --ansi

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations and seeders
php artisan migrate --force --seed

# Create storage symlink (ignore if already exists)
php artisan storage:link || true

exec apache2-foreground
