# =============================================================================
# Stage 1: Frontend Build (Node.js)
# =============================================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json vite.config.js ./
RUN npm ci

COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build

# =============================================================================
# Stage 2: Production — PHP 8.2-FPM
# =============================================================================
FROM php:8.2-fpm-bookworm AS production

# ---- Install install-php-extensions dari GitHub (bukan Docker Hub) ----
RUN curl -sSLf \
    "https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions" \
    -o /usr/local/bin/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions

# ---- Install semua PHP extensions ----
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    gd \
    zip \
    bcmath \
    opcache \
    intl \
    exif \
    pcntl \
    redis

# ---- Install system tools ----
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    git \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- Install Tesseract OCR ----
RUN apt-get update \
    && apt-get install -y --no-install-recommends tesseract-ocr \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Bahasa Indonesia (opsional — tidak gagalkan build jika tidak ada)
RUN apt-get update \
    && (apt-get install -y --no-install-recommends tesseract-ocr-ind \
        || echo "WARNING: tesseract-ocr-ind not available, OCR bahasa Indonesia tidak aktif") \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- PHP & FPM Configuration ----
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ---- Supervisor Configuration ----
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---- Install Composer binary ----
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ---- Application Files ----
WORKDIR /var/www/html

COPY . .

# ---- Install PHP dependencies ----
RUN COMPOSER_MEMORY_LIMIT=-1 COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-plugins \
    --prefer-dist \
    --ignore-platform-reqs \
    && composer clear-cache

# ---- Copy built frontend assets dari stage frontend ----
COPY --from=frontend /app/public/build ./public/build

# ---- Permissions ----
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# ---- Entrypoint & default command ----
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

# ENTRYPOINT: jalankan setup script
# CMD: default command (bisa dioverride oleh docker-compose)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm", "-F"]
