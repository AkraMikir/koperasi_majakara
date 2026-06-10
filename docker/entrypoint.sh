#!/bin/sh
# =============================================================================
# Docker Entrypoint — Koperasi Majakara
# Jalankan setup, lalu eksekusi CMD yang diberikan (php-fpm atau queue:work)
# =============================================================================

set -e

echo "============================================================"
echo " Koperasi Majakara — Starting Container"
echo " Command: $@"
echo "============================================================"

# ---- Hanya jalankan Laravel setup saat app/queue start ----
# (bukan saat build atau command lain seperti 'php artisan ...')
if [ "$1" = "php-fpm" ] || [ "$1" = "php" ]; then

    # ---- Fix permissions storage ----
    echo "[1/6] Setting permissions..."
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

    # ---- Storage link ----
    echo "[2/6] Creating storage symlink..."
    php artisan storage:link --force 2>/dev/null || true

    # ---- Cache config, routes, views ----
    echo "[3/6] Caching config..."
    php artisan config:cache

    echo "[4/6] Caching routes..."
    php artisan route:cache

    echo "[5/6] Caching views..."
    php artisan view:cache

    # ---- Database migration ----
    echo "[6/6] Running migrations..."
    php artisan migrate --force

    echo "============================================================"
    echo " Setup complete! Executing: $@"
    echo "============================================================"
fi

# Eksekusi CMD yang diberikan:
# - app container    → "php-fpm -F"     (dari CMD di Dockerfile)
# - queue container  → "php artisan queue:work ..." (dari command di docker-compose)
exec "$@"
