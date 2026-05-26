FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    zip \
    libpng-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy files
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissions
RUN mkdir -p storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN php artisan storage:link
# Expose Render port
EXPOSE 10000

# Start Laravel
CMD sh -c "php artisan storage:link && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"