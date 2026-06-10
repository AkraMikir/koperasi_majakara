# =============================================================================
# Stage 1: Frontend Build (Node.js)
# Build Vite assets (Tailwind CSS v4 + Alpine.js)
# =============================================================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy package files
COPY package.json package-lock.json vite.config.js ./

# Install npm dependencies
RUN npm ci

# Copy frontend source
COPY resources/ ./resources/
COPY public/ ./public/

# Build production assets
RUN npm run build

# =============================================================================
# Stage 2: Production Image
# PHP 8.2-FPM + Composer (inline) + Tesseract OCR + semua extensions
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
    # Process utilities
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

# ---- Install Composer (langsung di dalam PHP stage) ----
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ---- PHP Configuration ----
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ---- Supervisor Configuration ----
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---- Application Files ----
WORKDIR /var/www/html

# Copy source code dulu
COPY . .

# ---- Install PHP dependencies (dengan semua extensions sudah tersedia) ----
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    && composer clear-cache

# ---- Copy built frontend assets dari stage frontend ----
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
