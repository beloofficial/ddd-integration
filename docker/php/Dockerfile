FROM php:8.2-fpm

# ---- System packages & PHP extensions ----
RUN set -eux; \
    apt-get update -y; \
    apt-get install -y --no-install-recommends \
        bash git curl unzip \
        libicu-dev libzip-dev zlib1g-dev g++; \
    docker-php-ext-install intl opcache pdo_mysql zip; \
    pecl install apcu && docker-php-ext-enable apcu; \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# ---- Composer ----
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# ---- Symfony CLI (static binary, no bash script) ----
RUN curl -LsS https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony_linux_amd64 \
    -o /usr/local/bin/symfony && chmod +x /usr/local/bin/symfony

WORKDIR /var/www/project

COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]