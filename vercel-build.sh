#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate application key if not set
php artisan key:generate --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache

# Create necessary directories
mkdir -p storage/framework/{sessions,views,cache}
chmod -R 775 storage/framework 