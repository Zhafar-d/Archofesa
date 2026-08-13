#!/bin/bash
# Railway Quick Fix Script
# Jalankan di Railway Shell untuk clear semua cache dan rebuild

echo "🔧 Railway Quick Fix - Clearing all caches..."

echo "1️⃣ Clearing application cache..."
php artisan cache:clear

echo "2️⃣ Clearing config cache..."
php artisan config:clear

echo "3️⃣ Clearing route cache..."
php artisan route:clear

echo "4️⃣ Clearing view cache..."
php artisan view:clear

echo "5️⃣ Creating storage symlink..."
php artisan storage:link

echo "6️⃣ Checking database connection..."
php artisan db:show

echo "7️⃣ Checking migration status..."
php artisan migrate:status

echo "8️⃣ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Quick fix completed!"
echo ""
echo "Next steps:"
echo "- Test the admin panel edit page"
echo "- If still error, check Laravel logs: tail -f storage/logs/laravel.log"
echo "- Or enable APP_DEBUG=true in Railway variables"
