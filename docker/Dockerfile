# Set global versions and checksums
# GitHub
ARG DRUPAL_CONSOLE_VERSION=1.8.0
ARG DRUPAL_CONSOLE_SHA256SUM=1c9bef3d3e4f70633d5004e3a3d86d90630df7cf97dab46d0175855796f934da
ARG DRUSH_VERSION=0.6.0
ARG DRUSH_SHA256SUM=c3f32a800a2f18470b0010cd71c49e49ef5c087f8131eecfe9b686dc1f3f3d4e
ARG DART_SASS_VERSION=1.26.10
ARG DART_SASS_SHA256SUM=2eed8cf8ec08e59e14c3801819aacc296f09524dad10af4a886412053af45b82

# Apk
ARG ZLIB_DEV_VERSION=1.2.11-r3
ARG LIBPNG_DEV_VERSION=1.6.37-r1
ARG LIBZIP_VERSION=1.5.2-r0
ARG NODEJS_VERION=12.15.0-r1
ARG GIT_VERSION=2.24.3-r0
ARG MYSQL_CLIENT_VERSION=10.4.13-r0
ARG NGINX_VERSION=1.16.1-r6
ARG SUPERVISOR_VERSION=4.1.0-r0

# PHP Composer
ARG COMPOSER_VERSION=1.10.10

FROM php:7.4-alpine3.12 as builder

# load the versions and checksums needed in this stage
ARG COMPOSER_VERSION
ARG ZLIB_DEV_VERSION
ARG LIBPNG_DEV_VERSION
ARG LIBZIP_VERSION
ARG NODEJS_VERION
ARG GIT_VERSION
ARG MYSQL_CLIENT_VERSION
ARG DART_SASS_VERSION
ARG DART_SASS_SHA256SUM

ARG GOOGLE_MAPS_API_KEY

RUN set -ex \
        && curl -sSLO https://getcomposer.org/installer \
        && curl -sSLO https://composer.github.io/installer.sig \
        && php -r "if (hash_file('sha384', 'installer') === file_get_contents('installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
        && php installer --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} \
        && rm installer installer.sig

RUN apk add --update --no-cache \
        zlib-dev="$ZLIB_DEV_VERSION" \
        libpng-dev="$LIBPNG_DEV_VERSION" \
        libzip-dev="$LIBZIP_VERSION" \
        nodejs="$NODEJS_VERION" \
        npm="$NODEJS_VERION" \
        git="$GIT_VERSION" \
        && rm -rf /var/cache/apk/*

RUN docker-php-ext-install -j "$(nproc)" \
        gd \
        opcache \
        pdo \
        pdo_mysql \
        zip

WORKDIR /root

RUN set -ex \
        && curl -sSLO https://github.com/sass/dart-sass/releases/download/"$DART_SASS_VERSION"/dart-sass-"$DART_SASS_VERSION"-linux-x64.tar.gz \
        && [ $(sha256sum dart-sass-"$DART_SASS_VERSION"-linux-x64.tar.gz | cut -f1 -d' ') = "$DART_SASS_SHA256SUM" ] \
        && tar xzf dart-sass-"$DART_SASS_VERSION"-linux-x64.tar.gz \
        && rm dart-sass-"$DART_SASS_VERSION"-linux-x64.tar.gz
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
RUN npm install
RUN npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools
RUN npm install
RUN npm run build

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer dump-autoload --optimize \
        && composer run-script post-install-cmd

FROM php:7.4-fpm-alpine3.12

# load the versions and checksums needed in this stage
ARG DRUPAL_CONSOLE_VERSION
ARG DRUPAL_CONSOLE_SHA256SUM
ARG DRUSH_VERSION
ARG DRUSH_SHA256SUM
ARG ZLIB_DEV_VERSION
ARG LIBPNG_DEV_VERSION
ARG LIBZIP_VERSION
ARG MYSQL_CLIENT_VERSION
ARG NGINX_VERSION
ARG SUPERVISOR_VERSION

# non root user to run the server after binding sockets as root
ENV NGINX_DOC_ROOT /var/www/html/web

WORKDIR /var/www/html

RUN apk add --update --no-cache \
        nginx="$NGINX_VERSION" \
        supervisor="$SUPERVISOR_VERSION"\
        zlib-dev="$ZLIB_DEV_VERSION" \
        libpng-dev="$LIBPNG_DEV_VERSION" \
        libzip-dev="$LIBZIP_VERSION" \
        mysql-client="$MYSQL_CLIENT_VERSION" \
        && rm -rf /var/cache/apk/*

RUN docker-php-ext-install -j "$(nproc)" \
        gd \
        opcache \
        pdo \
        pdo_mysql \
        zip

RUN set -ex \
        && curl -sSLO https://github.com/hechoendrupal/drupal-console-launcher/releases/download/"$DRUPAL_CONSOLE_VERSION"/drupal.phar \
        && [ $(sha256sum drupal.phar | cut -f1 -d' ') = "$DRUPAL_CONSOLE_SHA256SUM" ] \
        && mv drupal.phar /usr/local/bin/drupal \
        && chmod +x /usr/local/bin/drupal

RUN set -ex \
        && curl -sSLO https://github.com/drush-ops/drush-launcher/releases/download/"$DRUSH_VERSION"/drush.phar \
        && [ $(sha256sum drush.phar | cut -f1 -d' ') = "$DRUSH_SHA256SUM" ] \
        && mv drush.phar /usr/local/bin/drush \
        && chmod +x /usr/local/bin/drush


COPY docker/php/php.ini /usr/local/etc/php
COPY docker/supervisord.conf /etc/
COPY docker/nginx.conf /etc/nginx/

RUN chown -R nobody.nobody /var/www/html \
        && chown -R nobody.nobody /run \
        && chown -R nobody.nobody /var/lib/nginx \
        && chown -R nobody.nobody /var/log/nginx

# copy in the files that the builder image built for drupal
COPY --chown=nobody:nobody --from=builder /var/www/html /var/www/html

USER nobody

EXPOSE 8080

STOPSIGNAL SIGTERM

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
