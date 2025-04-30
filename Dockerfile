# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Instalamos sistema, PostgreSQL-dev, Composer y extensiones PHP necesarias
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
      composer \
      php82-ctype \
      php82-xml \
      php82-sodium \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql \
  && docker-php-ext-enable sodium

# 2. Directorio de la aplicación
WORKDIR /app

# 3. Copiamos todo el código (incluye bin/console)
COPY . /app

# 4. Instalamos dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 5. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
