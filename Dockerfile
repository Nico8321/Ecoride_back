FROM php:8.2-fpm

# Installation des dépendances système nécessaires (Mongo, zip, mbstring, etc.)
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libssl-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# Installation de l'extension MongoDB via PECL
RUN pecl install mongodb \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définit le dossier de travail
WORKDIR /var/www/html

# Copie le code dans le conteneur
COPY . .

# Installation les dépendances PHP 
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissions 
RUN chown -R www-data:www-data /var/www/html

# Le conteneur PHP-FPM démarre automatiquement avec l'image
CMD ["php-fpm"]