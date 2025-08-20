FROM php:8.0-apache

# Install PostgreSQL dev libraries and PHP extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite

COPY . /var/www/html/
WORKDIR /var/www/html/
EXPOSE 80
