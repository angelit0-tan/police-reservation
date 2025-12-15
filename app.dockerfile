FROM php:8.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    vim \
    libmagickwand-dev \
    libldap2-dev \
    && docker-php-ext-configure ldap \
    && docker-php-ext-install ldap \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql bcmath exif

RUN curl -sL https://deb.nodesource.com/setup_20.x  | bash - && \
apt-get install -y nodejs && \
npm install --global --unsafe-perm cross-env

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]