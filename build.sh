#!/bin/bash

# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Generate application key if not set
php artisan key:generate --force

# Optimize Laravel
php artisan optimize

# Create storage symlink if needed
php artisan storage:link || true

# Run database migrations if needed
# php artisan migrate --force

# Clear cached config files
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "Build completed successfully" 