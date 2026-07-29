FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts \
    && composer dump-autoload --optimize

EXPOSE 8000

RUN cp .env.example .env

CMD rm -rf public/storage && \
    php artisan storage:link --force && \
    php artisan key:generate --force && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:clear && \
    php artisan serve --host=0.0.0.0 --port=8000
