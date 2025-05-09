#!/bin/bash
set -e

# Print current directory and list files
echo "Current directory: $(pwd)"
echo "Listing files in current directory:"
ls -la

# Change to the application directory
cd /var/www/html
echo "Changed to /var/www/html"
echo "Listing files in /var/www/html:"
ls -la

# Write Firebase credentials to file if provided
if [ ! -z "$FIREBASE_CREDENTIALS" ]; then
    echo "$FIREBASE_CREDENTIALS" > storage/app/firebase/firebase-credentials.json
    chmod 600 storage/app/firebase/firebase-credentials.json
    chown www-data:www-data storage/app/firebase/firebase-credentials.json
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# Execute the main command
echo "Starting Apache..."
exec "$@" 