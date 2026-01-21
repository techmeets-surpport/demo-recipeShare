FROM php:8.2-fpm

# 必要なパッケージインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# PostgreSQL用ライブラリ追加
RUN apt-get install -y libpq-dev

# PHP拡張インストール（MySQL + PostgreSQL両対応）
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 権限設定
RUN chown -R www-data:www-data /var/www/html
