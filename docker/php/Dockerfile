FROM php:8.0.7-fpm

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y wget git lsb-release gnupg unzip zlib1g-dev

RUN apt-get update && \
    apt-get install -y mariadb-client

RUN pecl install apcu pcov && \
    docker-php-ext-enable apcu pcov

RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html/evetradehelper
