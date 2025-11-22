FROM php:8.3-fpm

# Install Updates
RUN apt update -y \
    && apt upgrade -y \
    && apt install -y \
    zip \
    vim \
    less \
    && apt clean -y

# Install PHP Extensions
RUN apt install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install mysqli

# Install xdebug
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
ADD xdebug.ini /usr/local/etc/php/php.ini

# Install MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Redis
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer