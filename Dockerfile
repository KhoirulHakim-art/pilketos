FROM php:8.2-fpm

# Install dependencies sistem termasuk libpq-dev untuk PostgreSQL dan libzip-dev untuk zip extension
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip

# Install ekstensi PHP untuk Laravel & PostgreSQL (ditambah zip)
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT