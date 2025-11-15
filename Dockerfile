FROM php:8.2-fpm-alpine

# Nginx + Supervisor (pour lancer plusieurs services)
RUN apk add --no-cache nginx supervisor

# Extensions PHP
RUN apk add --no-cache autoconf gcc g++ make \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apk del autoconf gcc g++ make

# Dossier nécessaire pour nginx
RUN mkdir -p /run/nginx

# Config Nginx
COPY docker-nginx.conf /etc/nginx/http.d/default.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installation du projet PHP
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction

# Copie du projet
COPY . .

# Config Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf


CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]