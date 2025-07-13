FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev libpng-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Konfigurasi Apache
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/api_sudikap_laravel/public|g' /etc/apache2/sites-available/000-default.conf

# Permission (untuk development)
RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data