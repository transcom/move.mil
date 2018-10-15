FROM php:7.2 as builder

ENV DART_SASS_VERSION 1.7.0

RUN apt-get update && apt-get install -y gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get update && apt-get install -y --no-install-recommends \
        build-essential \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        mysql-client \
        git \
        nodejs \
    && docker-php-ext-install gd \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

RUN rm -rf /var/lib/apt/lists/*

ENV COMPOSER_COMMIT 877cb10b101957ef8bbb9d196f711dbb8a011bb4
RUN curl -o composer-setup.php --remote-name "https://raw.githubusercontent.com/composer/getcomposer.org/$COMPOSER_COMMIT/web/installer" | php -- --quiet > 'composer-setup.php' \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /root

RUN curl -L --remote-name https://github.com/sass/dart-sass/releases/download/$DART_SASS_VERSION/dart-sass-$DART_SASS_VERSION-linux-x64.tar.gz
RUN tar xzf dart-sass-$DART_SASS_VERSION-linux-x64.tar.gz && rm dart-sass-$DART_SASS_VERSION-linux-x64.tar.gz

ENV PATH="/root/dart-sass:$PATH"

# The following section is all in the name of container caching. We copy over the code base AFTER building deps.
# https://www.sentinelstand.com/article/composer-install-in-dockerfile-without-breaking-cache
# http://bitjudo.com/blog/2014/03/13/building-efficient-dockerfiles-node-dot-js/

WORKDIR /var/www/html
COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/
RUN composer install --no-scripts --no-autoloader

COPY web/themes/custom/move_mil/ /var/www/html/web/themes/custom/move_mil/
COPY web/modules/custom/react_tools/ /var/www/html/web/modules/custom/react_tools/

WORKDIR /var/www/html/web/themes/custom/move_mil/
RUN npm install && npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools
RUN npm install
RUN npm run build

COPY . /var/www/html
COPY web/.htaccess /var/www/html/web

WORKDIR /var/www/html

RUN composer dump-autoload --optimize && \
        composer run-script post-install-cmd

# Build our runtime image.
FROM php:7.2-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/web
ENV DRUPAL_CONSOLE_VERSION 1.8.0
ENV DRUSH_VERSION 0.6.0

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends mysql-client libpng-dev\
    && docker-php-ext-install gd \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/php.ini /usr/local/etc/php
COPY docker/apache/conf-enabled/ /etc/apache2/conf-enabled

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

RUN curl https://github.com/hechoendrupal/drupal-console-launcher/releases/download/$DRUPAL_CONSOLE_VERSION/drupal.phar -L -o drupal.phar
RUN mv drupal.phar /usr/local/bin/drupal
RUN chmod +x /usr/local/bin/drupal

RUN curl https://github.com/drush-ops/drush-launcher/releases/download/$DRUSH_VERSION/drush.phar -L -o drush.phar
RUN mv drush.phar /usr/local/bin/drush
RUN chmod +x /usr/local/bin/drush

COPY --from=builder /var/www/html /var/www/html

EXPOSE 80