# ================================
# Stage 1: Builder
# ================================
FROM php:8.4-fpm-alpine AS builder

# Install system dependencies
RUN apk update && apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    zip \
    unzip \
    shadow \
    oniguruma-dev \
    bash \
    gcc \
    g++ \
    make \
    autoconf

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pcntl \
    mbstring \
    exif \
    gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --prefer-dist \
    --no-ansi \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --no-dev \
    --optimize-autoloader

COPY . .

RUN composer dump-autoload --optimize --no-dev

# ================================
# Stage 2: Frontend build (Vite)
# ================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources

RUN npm ci
RUN npm run build

# ================================
# Stage 3: Production
# ================================
FROM php:8.4-fpm-alpine AS production

RUN apk update && apk add --no-cache \
    curl \
    libpng \
    libjpeg-turbo \
    freetype \
    libxml2 \
    oniguruma \
    shadow \
    nginx \
    supervisor \
    ca-certificates

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /var/www/html
COPY --from=builder /app .

# Copy Vite build output (manifest.json + assets)
COPY --from=frontend /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]