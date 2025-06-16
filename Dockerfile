FROM php:8.4-fpm

# Zainstaluj potrzebne paczki systemowe i rozszerzenia PHP
RUN apt-get update && apt-get install -y \
    git unzip zip curl gnupg2 libicu-dev libzip-dev libonig-dev libxml2-dev

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install intl pdo pdo_mysql zip opcache

# Instalacja Node.js 18.x i npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalacja yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn

# Skopiuj plik php.ini (jeśli masz)
COPY .docker/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
