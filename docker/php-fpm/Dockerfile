FROM php:8.2-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash
RUN apk add --update linux-headers

# Install Node.js
RUN apk add --no-cache nodejs npm
RUN npm install -g n
RUN n 18.14.0

# Install Yarn
RUN npm install -g yarn




# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql
# Install ext-redis
RUN pecl install redis && docker-php-ext-enable redis


# Nginx
RUN apk add --update --no-cache nginx
COPY ./docker/php-fpm/nginx.conf /etc/nginx/
RUN chown -Rf www-data:www-data /var/lib/nginx

# Supervisor
RUN apk add --no-cache supervisor
COPY ./docker/php-fpm/supervisord.conf /etc/supervisord.conf

# Source code
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www



# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN wget https://github.com/symfony/cli/releases/download/v4.28.1/symfony_linux_amd64.gz && \
    gunzip symfony_linux_amd64.gz && \
    mv symfony_linux_amd64 /usr/local/bin/symfony && \
    chmod +x /usr/local/bin/symfony

RUN wget https://get.symfony.com/cli/installer -O - | bash

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 8080

CMD ["/bin/sh", "./docker/php-fpm/run.sh"]

