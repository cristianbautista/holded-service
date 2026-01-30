FROM php:8.2-fpm

# Dependencias básicas
RUN apt-get update && apt-get install -y \
    git unzip zip curl nano \
    libzip-dev \
 && docker-php-ext-install zip opcache \
 && rm -rf /var/lib/apt/lists/*

# Xdebug
RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

# Copiar configuración de Xdebug
COPY docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
CMD ["php-fpm"]
