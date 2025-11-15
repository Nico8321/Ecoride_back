FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx supervisor gettext autoconf gcc g++ make

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN mkdir -p /run/nginx

COPY docker-nginx.conf /etc/nginx/http.d/default.conf

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

COPY . .

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENV PORT=8080
EXPOSE 8080

CMD envsubst '$PORT' < /etc/nginx/http.d/default.conf > /etc/nginx/http.d/default.conf.tmp \
    && mv /etc/nginx/http.d/default.conf.tmp /etc/nginx/http.d/default.conf \
    && /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
