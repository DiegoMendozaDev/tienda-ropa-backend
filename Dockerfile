# Etapa 1: Binario de Composer
FROM composer:2.5 AS composer-bin

# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# Copiamos Composer desde la etapa anterior
COPY --from=composer-bin /usr/bin/composer /usr/local/bin/composer

# Resto de instalaciones
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql

WORKDIR /app

COPY composer.json composer.lock /app/
RUN composer install --no-dev --optimize-autoloader

COPY . /app
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
