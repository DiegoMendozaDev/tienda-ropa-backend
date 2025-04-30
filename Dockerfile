FROM php:8.2-fpm-alpine

# 1. Habilitar repositorio community y actualizar índice
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/community" \
      >> /etc/apk/repositories \
  && apk update

# 2. Instalar sistema, librerías de desarrollo y extensiones PHP
RUN apk add --no-cache \
      bash \
      curl \
      nginx \
      postgresql-dev \
      libxml2-dev \
      libsodium-dev \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      pdo_pgsql \
      ctype \
      xml \
      sodium \
  && docker-php-ext-enable sodium

# 2.b Instalar Composer manualmente
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

# 3. Directorio de la aplicación y copia del código
WORKDIR /app
COPY . /app

# 4. Instalar dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# 5. Configuración de Nginx
COPY config/nginx/vhost.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["sh", "-c", "nginx && php-fpm"]
