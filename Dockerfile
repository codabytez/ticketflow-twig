# Use an official PHP image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
RUN apt-get update && apt-get install -y zip unzip git && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Install PHP dependencies
RUN composer install

# Expose port 3002 (Render expects this)
EXPOSE 3002

# Start PHP server
CMD ["php", "-S", "0.0.0.0:3002", "-t", "src"]
