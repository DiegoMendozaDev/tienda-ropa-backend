# 1. Etapa de dependencias PHP
FROM php:8.1-fpm-alpine AS composer_deps
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 2. (Opcional) Etapa de assets front-end
FROM node:16-alpine AS assets_build
WORKDIR /app
COPY package.json yarn.lock ./
RUN yarn install
COPY webpack.config.js postcss.config.js assets/ ./assets/
RUN yarn encore production

# 3. Imagen final de ejecución
FROM php:8.1-fpm-alpine
WORKDIR /app
# Instalar nginx y demás utilidades
RUN apk add --no-cache nginx bash
COPY --from=composer_deps /app/vendor /app/vendor
COPY --from=assets_build /app/public/build /app/public/build
COPY . /app
# Configuración de nginx y php-fpm
COPY infrastructure/php-fpm/*.conf /usr/local/etc/php-fpm.d/
COPY infrastructure/nginx/nginx.conf /etc/nginx/nginx.conf
COPY infrastructure/nginx/vhost.conf /etc/nginx/conf.d/default.conf
# Redirigir logs a stdout/stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log
EXPOSE 8080
# Opcional: usar Shoreman para orquestar PHP-FPM y nginx
COPY Procfile /app/Procfile
CMD ["shoreman"]
