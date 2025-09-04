# backend/Dockerfile
FROM php:8.2-fpm

# 安裝系統套件
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# 安裝 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www/html

# 複製專案
COPY . .

# 安裝 PHP 套件
RUN composer install

# 暴露端口
EXPOSE 8000

# 啟動 Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8000
