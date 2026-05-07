#!/bin/bash

# Run migrations
php /var/www/artisan migrate --force

# Start PHP-FPM in background
php-fpm -D

# Start Nginx
nginx -g "daemon off;"