# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Habilitar repositorio community y actualizar índice
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/community" \
      >> /etc/apk/repositories \
  && apk update

# 2. Instalar sistema, Composer, PostgreSQL-dev y extensiones PHP requeridas
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

# 3. Directorio de la aplicación y copia del código
WORKDIR /app
COPY . /app

# 4. Instalar dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# 5. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
