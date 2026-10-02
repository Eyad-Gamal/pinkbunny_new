# ── Stage 1: Build Vite assets ──────────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── Stage 2: PHP + Apache ────────────────────────────────────────────────────
FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libsqlite3-dev \
        libpng-dev \
        libonig-dev \
    && docker-php-ext-install \
        pdo \
        pdo_sqlite \
        zip \
        gd \
        mbstring \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Point Apache document root to Laravel's public/ folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

# Bring in Composer from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy compiled Vite assets from the assets stage
COPY --from=assets /app/public/build public/build

# Install PHP dependencies (no dev, optimised autoloader)
# --no-scripts: skips post-autoload-dump (package:discover) which needs a .env to boot Laravel
RUN composer install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts \
    && chown -R www-data:www-data storage bootstrap/cache database

# Copy and enable the custom entrypoint
COPY docker-entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

CMD ["entrypoint"]