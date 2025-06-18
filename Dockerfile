FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    sendmail \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf