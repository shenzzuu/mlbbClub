# Use official PHP with Apache image
FROM php:8.2-apache

# Enable mod_rewrite (if using friendly URLs or .htaccess)
RUN a2enmod rewrite

# Install PostgreSQL support
RUN apt-get update && apt-get install -y unzip libpq-dev && docker-php-ext-install pdo_pgsql

# Optional: install Composer if needed (Stripe SDK etc.)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# Copy project files (adjust if using /public folder)
COPY . /var/www/html

# Permissions fix (optional)
RUN chown -R www-data:www-data /var/www/html
