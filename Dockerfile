FROM php:8.2-fpm

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

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Perintah CMD ditambah storage:link agar foto yang di-upload langsung publik
CMD rm -rf public/storage && php artisan storage:link && php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT