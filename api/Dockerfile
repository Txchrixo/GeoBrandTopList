FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath gd opcache zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/api

COPY . .

RUN composer install --no-interaction --prefer-dist --no-scripts

# RUN groupadd -g 1000 appuser && \
#     useradd -u 1000 -g appuser -m -d /home/appuser -s /bin/bash appuser

# RUN chown -R appuser:appuser /var/www/api

# RUN sed -i 's/^user =.*/user = appuser/' /usr/local/etc/php-fpm.d/www.conf && \
#     sed -i 's/^group =.*/group = appuser/' /usr/local/etc/php-fpm.d/www.conf

# RUN sed -i 's/^listen = .*/listen = 9000/' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
