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

# ---- Step 1: Update apt & install system libs dasar ----
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    git \
    zip \
    supervisor \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- Step 2: Install libwebp (terpisah, lebih mudah debug) ----
RUN apt-get update && apt-get install -y --no-install-recommends \
    libwebp-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- Step 3: Install Tesseract OCR (terpisah) ----
RUN apt-get update && apt-get install -y --no-install-recommends \
    tesseract-ocr \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install bahasa Indonesia (opsional, tidak gagalkan build jika tidak ada)
RUN apt-get update \
    && apt-get install -y --no-install-recommends tesseract-ocr-ind \
    || (echo "WARNING: tesseract-ocr-ind not found, skipping" && true) \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- Step 4: Install PHP extensions ----
RUN docker-php-ext-configure gd \
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
        pcntl

# ---- Step 5: Install PHP Redis extension ----
RUN pecl install redis \
    && docker-php-ext-enable redis

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
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    && composer clear-cache

# ---- Copy built frontend assets ----
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
