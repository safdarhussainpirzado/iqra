FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    libzip-dev \
    tesseract-ocr \
    tesseract-ocr-data-eng \
    tesseract-ocr-data-urd \
    tesseract-ocr-data-sin

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql bcmath gd soap intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
