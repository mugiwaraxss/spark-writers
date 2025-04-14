FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy Docker-specific .env file
COPY docker/.env /var/www/html/.env

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Apache configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Create startup script
RUN echo '#!/bin/bash\n\
# Apply optimizations\n\
php artisan optimize\n\
php artisan config:clear\n\
php artisan route:clear\n\
\n\
# Start Apache\n\
apache2-foreground\n\
' > /usr/local/bin/start-apache && \
chmod +x /usr/local/bin/start-apache

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start using our script
CMD ["/usr/local/bin/start-apache"] 