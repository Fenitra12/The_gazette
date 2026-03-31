FROM php:8.4-fpm

# Installer les dépendances système pour PostgreSQL et GD
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd

# Définir le répertoire de travail
WORKDIR /var/www/html
