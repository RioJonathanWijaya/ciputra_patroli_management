#!/bin/bash
set -e

# Change to the application directory
cd /var/www/html

# Write Firebase credentials to file if provided
if [ ! -z "$FIREBASE_CREDENTIALS" ]; then
    echo "$FIREBASE_CREDENTIALS" > storage/app/firebase/firebase-credentials.json
    chmod 600 storage/app/firebase/firebase-credentials.json
    chown www-data:www-data storage/app/firebase/firebase-credentials.json
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

# Set proper permissions
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# Execute the main command
exec "$@" 