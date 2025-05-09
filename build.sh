#!/usr/bin/env bash

# exit on error
set -o errexit

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

# Create Firebase credentials directory
mkdir -p storage/app/firebase
chmod -R 775 storage/app/firebase

# If FIREBASE_CREDENTIALS is set, write it to a file
if [ ! -z "$FIREBASE_CREDENTIALS" ]; then
    echo "$FIREBASE_CREDENTIALS" > storage/app/firebase/firebase-credentials.json
    chmod 600 storage/app/firebase/firebase-credentials.json
fi 