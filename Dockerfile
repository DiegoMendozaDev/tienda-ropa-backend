FROM php:8.2-fpm-alpine

# 1. Instala dependencias de sistema y cabeceras de PostgreSQL
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql

# 2. Configura logs de Nginx
RUN mkdir -p /var/log/nginx \
 && touch /var/log/nginx/access.log /var/log/nginx/error.log \
 && ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log

# 3. Directorio de la aplicación
WORKDIR /app

# 4. Copia y instala dependencias de PHP
COPY composer.json composer.lock /app/
RUN composer install --no-dev --optimize-autoloader

# 5. Copia el código y configura Nginx
COPY . /app
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
