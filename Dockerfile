FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apk update && \
    apk add --no-cache \
        libzip-dev \
        unzip \
        git \
        autoconf \
        g++ \
        make \
        openssl-dev \
        icu-dev  \
        icu-libs \
        icu-data-full \
        freetype \
        libjpeg-turbo \
        libpng \
        libjpeg-turbo-dev \
        libpng-dev \
        libxml2-dev \
        oniguruma-dev \
     && docker-php-ext-install \
         zip \
         pdo \
        pdo_mysql \
        intl \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        opcache 

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Install application dependencies
RUN composer install

# Create the symbolic link for Laravel storage
RUN php artisan storage:link

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html

# Expose port
EXPOSE 80 9000
