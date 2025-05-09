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

# Create necessary directories with proper permissions
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create temp directory for Firebase
mkdir -p /tmp/firebase
chmod -R 775 /tmp/firebase

# Set proper permissions for storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Ensure PHP has write access to temp directories
chmod -R 777 /tmp 