FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql bcmath pcntl zip intl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/security.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Same uid as the usual Linux user, so files written to the mounted project belong to them.
RUN groupadd --gid 1000 app \
    && useradd --uid 1000 --gid app --create-home --shell /bin/bash app

# Strip setuid/setgid bits from base image programs (su, passwd, mount) the app never uses.
RUN find / -xdev -perm /6000 -type f -exec chmod a-s {} +

WORKDIR /var/www/html

USER app
