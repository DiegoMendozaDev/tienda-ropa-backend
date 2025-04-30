FROM php:8.2-fpm-alpine

# 1. Actualizar repositorios y paquetes base
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/community" \
      >> /etc/apk/repositories \
  && apk update \
  && apk add --no-cache bash curl nginx postgresql-dev libxml2-dev libsodium-dev \
  && docker-php-ext-install pdo pdo_mysql pdo_pgsql ctype xml sodium \
  && docker-php-ext-enable sodium

# 2. Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

# 3. Permitir plugins al correr como root
ENV COMPOSER_ALLOW_SUPERUSER=1

# 4. Copiar código y dependencias
WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer require symfony/flex --no-interaction --no-progress
# Instalar dependencias (incluye dev para que MakerBundle esté disponible al registrar bundles)
RUN composer install --no-interaction --optimize-autoloader
COPY . /app

# 5. Instalar dependencias y optimizar autoload
RUN composer install --no-dev --no-scripts --optimize-autoloader

# 4) (Opcional) Generar caché prod manualmente
RUN php bin/console cache:clear --env=prod --no-warmup \
 && php bin/console cache:warmup --env=prod

# Crea logs de Nginx
RUN mkdir -p /var/log/nginx \
&& touch /var/log/nginx/access.log /var/log/nginx/error.log \
&& ln -sf /dev/stdout /var/log/nginx/access.log \
&& ln -sf /dev/stderr /var/log/nginx/error.log
# Copia el principal
COPY config/nginx/nginx.conf /etc/nginx/nginx.conf
# 6. Configurar Nginx y lanzar servicios
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]