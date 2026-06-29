FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libcurl4-openssl-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-install zip pdo_mysql curl mbstring gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "index.php"]
#TODO fazer um router ou resolver no index
