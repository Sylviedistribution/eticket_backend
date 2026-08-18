FROM php:8.2-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier Composer en premier pour profiter du cache Docker
COPY composer.json composer.lock ./

# Installer les dépendances sans exécuter les scripts Laravel
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Copier tout le projet Laravel
COPY . .

# Générer l'autoload maintenant que artisan existe
RUN composer dump-autoload \
    --no-dev \
    --optimize

# Permissions Laravel
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Configuration Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Script de démarrage
COPY docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]