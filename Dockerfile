# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Instalamos sistema, PostgreSQL-dev, Composer y extensiones requeridas
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
      composer \
      php8-ctype \      # instala ext-ctype :contentReference[oaicite:4]{index=4}
      php8-xml   \      # instala ext-xml   :contentReference[oaicite:5]{index=5}
      php8-sodium       # instala ext-sodium :contentReference[oaicite:6]{index=6} \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql          # instala PDO-MySQL y PDO-PgSQL :contentReference[oaicite:7]{index=7} \
  && docker-php-ext-enable sodium  # habilita ext-sodium :contentReference[oaicite:8]{index=8}

# 2. Directorio de la aplicación
WORKDIR /app

# 3. Copiamos todo el código (incluye bin/console)
COPY . /app

# 4. Instalamos dependencias de PHP (ahora con las extensiones disponibles)
RUN composer install --no-dev --optimize-autoloader

# 5. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]

