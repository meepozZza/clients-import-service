# Базовый образ с PHP и Apache
FROM php:8.3-apache

# Установка зависимостей и расширений PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip \
    unzip \
    && docker-php-ext-install zip pdo pdo_pgsql gd \
    && docker-php-ext-enable gd

# Установка Redis расширения для PHP
RUN pecl install redis && docker-php-ext-enable redis

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Рабочая директория
WORKDIR /var/www/html

# Копирование исходного кода
COPY . .

# Установка зависимостей
RUN composer install --no-dev --optimize-autoloader

# Настройка прав
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Порт и команда запуска
EXPOSE 80
CMD ["apache2-foreground"]
