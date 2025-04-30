# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Instalamos dependencias de sistema, cabeceras de PostgreSQL y Composer
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
      composer \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql

# 2. Directorio de la aplicación
WORKDIR /app

# 3. Copiamos todo el código primero (incluye bin/, src/, config/, composer.json, etc.)
COPY . /app

# 4. Instalamos las dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 5. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

# 6. Exponemos el puerto y arrancamos Nginx + PHP-FPM
EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]

