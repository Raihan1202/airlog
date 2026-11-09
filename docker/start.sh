#!/bin/bash
set -e

echo "=== Airlog Startup Script ==="

# Wait for database to be ready (max 60 seconds)
echo "⏳ Waiting for database connection..."
for i in $(seq 1 30); do
    if php artisan migrate:status > /dev/null 2>&1; then
        echo "✅ Database connection established."
        break
    fi
    if [ $i -eq 30 ]; then
        echo "❌ Could not connect to database after 60 seconds. Exiting."
        exit 1
    fi
    echo "   Attempt $i/30 - Database not ready yet, waiting 2s..."
    sleep 2
done

# Run migrations
echo "🔄 Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database on first deploy (only if users table is empty)
echo "🌱 Checking initial database records..."
if php artisan db:seed --class=DatabaseSeeder --help > /dev/null 2>&1; then
    php artisan db:seed --force --no-interaction || true
else
    echo "⚠️ DatabaseSeeder not found or skipped."
fi

# Cache configuration for performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink (ignore if already exists)
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

echo "🚀 Starting Apache..."
exec apache2-foreground
