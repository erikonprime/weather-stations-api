FROM php:8.2-fpm

RUN apt update \
    && apt install -y zlib1g-dev libssl-dev g++ git libicu-dev zip libzip-dev zip libz-dev libmemcached-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu memcached \
    && docker-php-ext-enable apcu memcached \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && apt install -y librabbitmq-dev libssh-dev \
    && pecl install amqp-1.11.0 \
    && docker-php-ext-enable amqp \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
