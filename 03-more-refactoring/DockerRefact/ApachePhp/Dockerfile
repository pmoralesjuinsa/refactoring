FROM php:7.4.1-apache AS miapachephpcom

RUN apt-get update && apt-get install -y \
    unzip \
    wget

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
