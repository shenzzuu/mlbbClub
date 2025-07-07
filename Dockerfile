FROM php:8.2-cli

# Install PHP built-in server + any required extensions
RUN apt-get update && apt-get install -y unzip libpq-dev && docker-php-ext-install pdo_pgsql

# Copy all files to the container
COPY . /app
WORKDIR /app

# Start the built-in server
CMD ["php", "-S", "0.0.0.0:10000"]