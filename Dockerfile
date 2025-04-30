FROM php:8.2-fpm-alpine

WORKDIR /app

# Extensiones PHP
RUN apk add --no-cache \
    bash \
    postgresql-dev \
  && docker-php-ext-install pdo_pgsql

# Instalar dependencias de PHP
WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer install --no-dev --optimize-autoloader
# Copiar código
COPY . /app

# Build de assets (si los usas)
# (opcional) copiar node y yarn si lo necesitas
# RUN apk add --no-cache nodejs npm \
#  && npm install \
#  && npm run build

# Copia el principal
COPY config/nginx/nginx.conf /etc/nginx/nginx.conf

# Copiar la configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

# Instalar Nginx, crear logs y arrancar
RUN apk add --no-cache nginx bash \
 && mkdir -p /var/log/nginx \
 && touch /var/log/nginx/access.log /var/log/nginx/error.log \
 && ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

