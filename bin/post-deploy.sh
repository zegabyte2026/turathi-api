#!/bin/bash
set -e

echo "🔄 Running migrations..."
php artisan migrate --force

echo "🌱 Seeding database..."
php artisan db:seed --force

echo "🔑 Generating APP_KEY if empty..."
php artisan key:generate --force

echo "🧹 Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Post-deploy complete!"
