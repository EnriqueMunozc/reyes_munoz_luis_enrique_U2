FROM php:8.3-fpm-bookworm AS base

ARG SQLSRV_VERSION=5.12.0

ENV DEBIAN_FRONTEND=noninteractive \
    ACCEPT_EULA=Y

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        apt-transport-https \
        ca-certificates \
        curl \
        g++ \
        git \
        gnupg \
        libicu-dev \
        libxml2-dev \
        libonig-dev \
        libzip-dev \
        unzip \
        unixodbc-dev \
    && curl -fsSL https://packages.microsoft.com/config/debian/12/packages-microsoft-prod.deb -o /tmp/packages-microsoft-prod.deb \
    && dpkg -i /tmp/packages-microsoft-prod.deb \
    && rm /tmp/packages-microsoft-prod.deb \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql18 \
    && docker-php-ext-install -j"$(nproc)" bcmath intl mbstring opcache pcntl xml zip \
    && pecl install "sqlsrv-${SQLSRV_VERSION}" "pdo_sqlsrv-${SQLSRV_VERSION}" \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && apt-get purge -y --auto-remove g++ \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

WORKDIR /var/www/html

FROM base AS vendor

COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --optimize-autoloader

FROM node:22.15.0-bookworm-slim AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public
RUN npm run build

FROM base AS app

COPY --from=vendor /var/www/html/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build
RUN sed -i 's/\r$//' docker/entrypoint.sh docker/initialize.sh \
    && chmod +x docker/entrypoint.sh docker/initialize.sh \
    && mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= php artisan package:discover --ansi

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
CMD ["php-fpm"]

FROM nginx:1.27.5-alpine AS web

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY public /var/www/html/public
COPY --from=assets /app/public/build /var/www/html/public/build
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage
