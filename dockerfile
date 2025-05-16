# Базовый образ PHP с поддержкой Laravel
FROM php:8.2-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем всё в контейнер
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --optimize-autoloader --no-dev

# Даем нужные права
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Открываем порт (если нужно использовать php -S)
EXPOSE 9000

COPY start.sh /start.sh
CMD ["/start.sh"]
