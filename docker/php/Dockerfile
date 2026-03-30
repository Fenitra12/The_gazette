FROM php:8.4-fpm

# Installer les dépendances système pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Définir le répertoire de travail
WORKDIR /var/www/html
