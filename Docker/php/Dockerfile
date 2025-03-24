# Use PHP 8.2 FPM instead of Apache
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install required PHP extensions
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    fileinfo \
    gettext \
    zip \
    opcache \
    && docker-php-ext-enable \
    mysqli \
    pdo \
    pdo_mysql \
    fileinfo \
    gettext \
    zip \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom PHP configuration (optional)
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Set working directory
WORKDIR /var/www/html/RESTapi

# Expose the PHP port
EXPOSE 9000
