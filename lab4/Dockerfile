FROM php:8.2-fpm

# Устанавливаем утилиты для Composer и расширение для HTTP-запросов
RUN apt-get update && apt-get install -y git unzip libcurl4-openssl-dev \
    && docker-php-ext-install curl \
    && rm -rf /var/lib/apt/lists/*

# Копируем Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]