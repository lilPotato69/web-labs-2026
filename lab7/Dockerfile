FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        git unzip \
        librdkafka-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install sockets \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]