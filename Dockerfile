# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Habilitar repositorio community y actualizar índice
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/community" \
      >> /etc/apk/repositories \
  && apk update \
# 2. Instalar sistema, PostgreSQL-dev, Composer y extensiones PHP
  && apk add --no-cache \
       bash \
       curl \
       nginx \
       postgresql-dev \
       composer \
       php8-ctype \      # ext-ctype :contentReference[oaicite:4]{index=4}
       php8-xml   \      # ext-xml   :contentReference[oaicite:5]{index=5}
       php8-sodium       # ext-sodium :contentReference[oaicite:6]{index=6}
# 3. Instalar drivers de base de datos y habilitar sodium
  && docker-php-ext-install \
       pdo \
       pdo_mysql \
       pdo_pgsql \       # PDO para MySQL y PostgreSQL :contentReference[oaicite:7]{index=7}
  && docker-php-ext-enable sodium  # activa ext-sodium :contentReference[oaicite:8]{index=8}

# 4. Directorio de trabajo y código
WORKDIR /app
COPY . /app

# 5. Dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# 6. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]