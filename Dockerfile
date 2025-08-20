FROM php:8.0-apache

# 🐘 Install PostgreSQL dev libraries and PHP extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 🔁 Enable Apache mod_rewrite for routing
RUN a2enmod rewrite

# 🔓 Allow .htaccess overrides in Apache config
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 📁 Copy project files into Apache's web root
COPY . /var/www/html/

# 🔐 Set correct file ownership for Apache
RUN chown -R www-data:www-data /var/www/html

# 📍 Set working directory
WORKDIR /var/www/html/

# 🌐 Expose port 80
EXPOSE 80
