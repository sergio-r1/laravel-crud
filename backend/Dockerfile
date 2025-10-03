FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY .env .env

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN touch database/database.sqlite

RUN php artisan key:generate

RUN chown -R www-data:www-data storage bootstrap/cache

RUN php artisan migrate --force

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
