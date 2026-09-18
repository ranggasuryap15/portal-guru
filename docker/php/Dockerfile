# ==============================================================================
# Tujuan: Dockerfile untuk runtime PHP 8.4-FPM aplikasi Laravel portal-guru.
# Dipakai Oleh: docker-compose.yml (service: app)
# Dependensi: Base image php:8.4-fpm-alpine, Composer v2, libpng, libzip, oniguruma
# Fungsi Utama: Menyediakan environment PHP 8.4-FPM, instalasi ekstensi DB/utilitas, dan user permission non-root
# Side Effect: File I/O (build container image, permissions storage & cache)
# ==============================================================================

FROM php:8.4-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & build tools
RUN apk update && apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    linux-headers \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure user & group mapping to match host user (1000:1000)
ARG UID=1000
ARG GID=1000
RUN apk add --no-cache shadow && \
    usermod -u ${UID} www-data && \
    groupmod -g ${GID} www-data

# Copy custom php.ini configuration
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
