# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# 1. Instalar sistema, PostgreSQL-dev, Composer y extensiones PHP faltantes
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
      composer \
      php82-ctype \      
      php82-xml   \      
      php82-sodium       

# 2. Instalar controladores de bases de datos y habilitar Sodium
RUN docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql \
  && docker-php-ext-enable sodium

# 3. Directorio de la aplicación
WORKDIR /app

# 4. Copiar todo el código (incluye bin/console)
COPY . /app

# 5. Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 6. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
