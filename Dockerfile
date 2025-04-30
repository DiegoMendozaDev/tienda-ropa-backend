FROM php:8.2-fpm-alpine

# Instala dependencias de sistema y Composer
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      composer \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql

# Crea logs de Nginx
RUN mkdir -p /var/log/nginx \
 && touch /var/log/nginx/access.log /var/log/nginx/error.log \
 && ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log

# Directorio de la aplicación
WORKDIR /app

# Copia solo composer.json y composer.lock, e instala dependencias
COPY composer.json composer.lock /app/
RUN composer install --no-dev --optimize-autoloader

# Copia el resto del código
COPY . /app

# Copia configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

# Exponer puerto y arrancar supervisión de Nginx + PHP-FPM
EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
