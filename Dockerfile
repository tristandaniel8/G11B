FROM php:8.2-apache 

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    msmtp \
    msmtp-mta \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

# Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN a2ensite 000-default.conf

# PHP mail configuration
COPY src/php-sendmail.ini /usr/local/etc/php/conf.d/zz-sendmail.ini 
COPY src/msmtprc /etc/msmtprc 
RUN chmod 0644 /etc/msmtprc && chown www-data:www-data /etc/msmtprc

# Set recommended PHP.ini settings
# (Consider adding your production php.ini settings here)
RUN { \
    echo 'upload_max_filesize = 40M'; \
    echo 'post_max_size = 40M'; \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 60'; \
    echo 'date.timezone = Europe/Paris'; \
} > /usr/local/etc/php/conf.d/99-custom.ini

# Set working directory
WORKDIR /var/www/html

# Copy application source (optional, if not using volume mount for development)
# COPY src/ /var/www/html/

# Ensure www-data has correct permissions if files are copied
# RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]