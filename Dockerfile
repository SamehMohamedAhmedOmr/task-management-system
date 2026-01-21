FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files and install dependencies first (better cache)
COPY composer.json composer.lock /var/www/
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy existing application directory contents
COPY . /var/www

# Run post-install scripts now that artisan exists
RUN composer dump-autoload --optimize && php artisan package:discover --ansi

# Fix ownership for runtime
RUN chown -R www-data:www-data /var/www

# Set entrypoint to run migrations on start
COPY --chown=www-data:www-data docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# Change current user to www-data
USER www-data
