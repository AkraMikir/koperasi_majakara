# =============================================================================
# Stage 1: Frontend Build (Node.js)
# Build Vite assets (Tailwind CSS v4 + Alpine.js)
# =============================================================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy package files
COPY package.json package-lock.json vite.config.js ./

# Install npm dependencies
RUN npm ci --prefer-offline

# Copy frontend source
COPY resources/ ./resources/
COPY public/ ./public/

# Build production assets
RUN npm run build

# =============================================================================
# Stage 2: PHP Dependencies (Composer)
# =============================================================================
FROM composer:2.7 AS composer-deps

WORKDIR /app

COPY composer.json composer.lock ./

# Install production PHP deps (no dev)
RUN composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist

# =============================================================================
# Stage 3: Production Image
# PHP 8.2-FPM + Tesseract OCR + semua extensions
# =============================================================================
FROM php:8.2-fpm-bookworm AS production

# ---- System Dependencies & PHP Extensions ----
RUN apt-get update && apt-get install -y --no-install-recommends \
    # Utilities
    curl \
    unzip \
    git \
    # Image processing (GD)
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    # Zip
    libzip-dev \
    zip \
    # Intl
    libicu-dev \
    # PDF (DomPDF)
    libxml2-dev \
    # Tesseract OCR
    tesseract-ocr \
    tesseract-ocr-ind \
    # Proccess utilities
    supervisor \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        gd \
        zip \
        bcmath \
        opcache \
        intl \
        exif \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- PHP Configuration ----
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ---- Supervisor Configuration ----
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---- Application Files ----
WORKDIR /var/www/html

# Copy vendor dari stage composer
COPY --from=composer-deps /app/vendor ./vendor

# Copy built frontend assets dari stage frontend
COPY --from=frontend /app/public/build ./public/build

# Copy seluruh source code aplikasi
COPY . .

# Copy built assets lagi (pastikan tidak tertimpa)
COPY --from=frontend /app/public/build ./public/build

# ---- Permissions ----
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ---- Entrypoint ----
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
