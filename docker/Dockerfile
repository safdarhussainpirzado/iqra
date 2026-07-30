FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    tesseract-ocr \
    tesseract-ocr-data-eng \
    poppler-utils

# Download Urdu and Sindhi OCR training data directly
RUN curl -L -o /usr/share/tessdata/urd.traineddata https://github.com/tesseract-ocr/tessdata/raw/main/urd.traineddata && \
    curl -L -o /usr/share/tessdata/sin.traineddata https://github.com/tesseract-ocr/tessdata/raw/main/sin.traineddata

# Install highly-optimized PHP extensions via helper script (much faster, avoids compiling from source manually)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql bcmath gd soap intl zip pcntl redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
