FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    sqlite \
    sqlite-dev \
    libzip-dev \
    oniguruma-dev \
    curl \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite zip mbstring bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-interaction --optimize-autoloader

COPY . .

RUN cp .env.example .env
RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD sh -c '\
  mkdir -p /usr/share/nginx/database && \
  [ -f /usr/share/nginx/database/database.sqlite ] || touch /usr/share/nginx/database/database.sqlite && \
  php artisan migrate --force && \
  php-fpm