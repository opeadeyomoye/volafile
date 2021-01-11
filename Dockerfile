FROM php:7.4.11-apache

ARG DB_CERT
ARG DB_CERT_PATH

# install all the system dependencies and enable PHP modules
RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      mariadb-client \
      git \
      zlib1g-dev \
      libzip-dev \
      zip \
      unzip \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
      intl \
      # a bunch of extensions are already included in the base php image
      # see https://github.com/docker-library/php/issues/233#issuecomment-344417146
      # mbstring \
      # openssl \
      pcntl \
      pdo_mysql \
      zip \
      opcache

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# set our application folder as an environment variable
ENV APP_HOME /var/www/html

# change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# change the web_root to CakePHP /var/www/html/webroot folder
RUN sed -i -e "s/html/html\/webroot/g" /etc/apache2/sites-enabled/000-default.conf

# enable apache module rewrite
RUN a2enmod rewrite

# copy source files and run composer
COPY . $APP_HOME

# install all PHP dependencies
RUN composer install --no-interaction

# change ownership of our applications
RUN chown -R www-data:www-data $APP_HOME

# store ca cert for db connections
RUN echo $DB_CERT > $DB_CERT_PATH
