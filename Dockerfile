# Etapa final: PHP-FPM con Alpine
FROM php:8.2-fpm-alpine

# Instala dependencias de sistema y cabeceras
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev

# Directorio de la app
WORKDIR /app

# 1. Copia código y archivos de configuración desde el repositorio
COPY . /app

# 2. Instala dependencias de PHP (incluye la ejecución de @auto-scripts)
RUN composer install --no-dev --optimize-autoloader

# 3. Copia configuración de Nginx (si fuese externa)
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]

