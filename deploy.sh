#!/bin/bash
# Railway Deployment Script
# File ini akan dijalankan otomatis saat deploy di Railway

echo "🚀 Starting deployment..."

# Step 1: Clear all caches FIRST
echo "🧹 Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Step 2: Run migrations
echo "📊 Running migrations..."
php artisan migrate --force

# Step 3: Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Step 4: Optimize for production (NO CACHE YET - routes belum stabil)
echo "⚡ Optimizing..."
php artisan config:cache
# Jangan route:cache dulu karena masih ada perubahan
php artisan view:cache

echo "✅ Deployment completed successfully!"
