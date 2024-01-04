FROM php:8.2-fpm-alpine

# Установка необходимых PHP расширений
RUN apk --no-cache update \
    && apk --no-cache add \
        autoconf \
        g++ \
        make \
        linux-headers \
        openssl-dev

# Установка расширений PHP
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug
COPY xdebug.ini  $PHP_INI_DIR/conf.d/

# Copy php.ini
COPY ./php.ini /usr/local/etc/php/

# Копирование Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]
