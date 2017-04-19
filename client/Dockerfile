FROM php:7-apache

## Il va falloir trouver comment régler ce problème
RUN apt-get update

## installation modules php
RUN apt-get install -y zlib1g-dev libicu-dev g++ \
 && docker-php-ext-configure intl \
 && docker-php-ext-install pdo_mysql intl

## Ajout locale
# RUN apt-get install locales -y && sed -i "s|.*fr_FR.UTF-8 UTF-8.*|fr_FR.UTF-8 UTF-8|g" /etc/locale.gen && locale-gen

## ajustements de la config apache
RUN a2enmod rewrite
RUN sed -i "s|DocumentRoot.*|DocumentRoot /var/www/html/web|g" /etc/apache2/sites-available/000-default.conf

## inclu le source
COPY . /var/www/html/

## Expose le port
EXPOSE 80
