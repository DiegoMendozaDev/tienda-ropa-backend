FROM php:8.2-fpm-alpine

WORKDIR /app

# Extensiones PHP
RUN apk add --no-cache \
    bash \
    postgresql-dev \
  && docker-php-ext-install pdo_pgsql

# Copiar dependencias de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copiar código
COPY . /app

# Build de assets (si los usas)
# (opcional) copiar node y yarn si lo necesitas
# RUN apk add --no-cache nodejs npm \
#  && npm install \
#  && npm run build

# Instala nginx y bash
RUN apk add --no-cache nginx bash

# Crea directorio y archivos de log antes de enlazar
RUN mkdir -p /var/log/nginx \
 && touch /var/log/nginx/access.log /var/log/nginx/error.log \
 && ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log

# Copiar configuración de nginx si la tienes
# COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf
# COPY config/nginx/nginx.conf  /etc/nginx/nginx.conf

# Exponer puerto (el que uses en Render; por defecto 8080)
EXPOSE 8080

# Comando de arranque — aquí puedes usar php-fpm + nginx directamente
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

