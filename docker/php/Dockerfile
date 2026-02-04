ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-fpm

# System deps + PHP extensions needed by Laravel
RUN set -eux; \
  apt-get update -o Acquire::Retries=3; \
  apt-get install -y --no-install-recommends \
    $PHPIZE_DEPS \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    openssl \
    ca-certificates; \
  docker-php-ext-configure intl; \
  docker-php-ext-install -j"$(nproc)" \
    pdo_mysql \
    intl \
    zip \
    bcmath \
    opcache; \
  apt-get purge -y --auto-remove $PHPIZE_DEPS; \
  rm -rf /var/lib/apt/lists/*

# Latest Composer (v2) from the official image
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Helpful defaults for dev
RUN { \
      echo "opcache.enable=1"; \
      echo "opcache.enable_cli=1"; \
      echo "opcache.validate_timestamps=1"; \
      echo "opcache.revalidate_freq=0"; \
    } > /usr/local/etc/php/conf.d/opcache-dev.ini
