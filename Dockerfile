# Dockerfile
FROM php:8.2-apache

# System deps (optional but common)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions typically required for MySQL access
RUN docker-php-ext-install -j"$(nproc)" mysqli pdo pdo_mysql

# Enable Apache modules often used by PHP monoliths
RUN a2enmod rewrite headers

# Set Apache document root (default is already /var/www/html)
WORKDIR /var/www/html

# Copy application into the image
COPY . /var/www/html

# Reasonable permissions for Apache in the container
RUN chown -R www-data:www-data /var/www/html