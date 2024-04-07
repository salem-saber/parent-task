# Use official PHP image as base
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# Copy application code
COPY . .

# Install application dependencies
RUN composer install  --ignore-platform-reqs

# Expose port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
