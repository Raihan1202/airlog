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
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "🌱 Seeding database with initial data..."
    php artisan db:seed --force --no-interaction || true
else
    echo "✅ Database already has data, skipping seed."
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
