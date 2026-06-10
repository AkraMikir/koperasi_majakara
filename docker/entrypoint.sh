#!/bin/sh
# =============================================================================
# Docker Entrypoint — Koperasi Majakara (PHP App)
# Dijalankan setiap kali container app dimulai
# =============================================================================

set -e

echo "============================================================"
echo " Koperasi Majakara — Starting Container"
echo "============================================================"

# ---- Storage link ----
echo "[1/6] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# ---- Cache config, routes, views (production optimization) ----
echo "[2/6] Caching configuration..."
php artisan config:cache

echo "[3/6] Caching routes..."
php artisan route:cache

echo "[4/6] Caching views..."
php artisan view:cache

# ---- Database migration ----
echo "[5/6] Running database migrations..."
php artisan migrate --force

# ---- Fix storage permissions ----
echo "[6/6] Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "============================================================"
echo " Container ready! Starting PHP-FPM..."
echo "============================================================"

# ---- Start PHP-FPM ----
exec /usr/local/sbin/php-fpm -F
