FROM --platform=linux/x86_64 php:8.1.18-fpm-alpine

WORKDIR /var/www/html/

RUN apk update
RUN apk add --update curl-dev oniguruma-dev libxml2-dev libzip-dev postgresql-dev supervisor

RUN #docker-php-ext-install fileinfo mbstring pdo xml
RUN docker-php-ext-install zip curl
RUN docker-php-ext-install mysqli pdo pdo_mysql


# Setup GD extension
RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/*


RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini $PHP_INI_DIR/php.ini

# COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Add Supervisor configuration
COPY supervisor.conf /etc/supervisor.d/
COPY supervisord.log /var/log/supervisord/
COPY supervisord.pid /var/run/supervisord/
