# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Habilitar repositorio community y actualizar índice
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/community" \
      >> /etc/apk/repositories \
  && apk update \
  \
# 2. Instalar sistema, PostgreSQL-dev, Composer y extensiones PHP necesarias:
#    - php8-ctype: ext-ctype
#    - php8-xml:   ext-xml
#    - php8-sodium: ext-sodium
  && apk add --no-cache \
       bash \
       curl \
       nginx \
       postgresql-dev \
       composer \
       php82-ctype \
       php82-xml \
       php82-sodium \
  \
# 3. Instalar drivers de base de datos y habilitar sodium
  && docker-php-ext-install \
       pdo \
       pdo_mysql \
       pdo_pgsql \
  && docker-php-ext-enable sodium

# 4. Directorio de la aplicación
WORKDIR /app

# 5. Copiar todo el código (incluye bin/console) y ejecutar Composer
COPY . /app
RUN composer install --no-dev --optimize-autoloader

# 6. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]