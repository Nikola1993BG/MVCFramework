FROM php:8.2-fpm-alpine
WORKDIR /var/www/html
RUN docker-php-ext-install mysqli pdo pdo_mysql