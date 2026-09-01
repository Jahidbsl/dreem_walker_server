FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libicu-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including intl and zip
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd intl zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory
COPY . /var/www/html

# Copy custom apache configuration file
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache Mod Rewrite
RUN a2enmod rewrite

# Install project dependencies
RUN composer install --no-dev --optimize-autoloader

# Publish Filament assets during build (not at runtime)
RUN php artisan filament:assets --ansi || true

# Set permissions and storage link
RUN php artisan storage:link || true
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer config --global process-timeout 600 && \
    composer install --no-dev --optimize-autoloader --no-progress || \
    (sleep 10 && composer install --no-dev --optimize-autoloader --no-progress)

EXPOSE 80

CMD php artisan config:clear && php artisan cache:clear && php artisan optimize && apache2-foreground