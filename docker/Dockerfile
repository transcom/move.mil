# build site dependencies from debian buster php 7.4 image
FROM php:7.4-buster as builder

ENV DART_SASS_VERSION 1.26.10

ARG GOOGLE_MAPS_API_KEY

RUN apt-get update && apt-get install -y gnupg
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get update && apt-get install -y --no-install-recommends \
	libzip-dev \
	build-essential \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libpng-dev \
	default-mysql-client \
	git \
	nodejs \
	&& docker-php-ext-install \
	gd \
	opcache \
	pdo \
	pdo_mysql \
	zip


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
RUN npm install
RUN npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools
RUN npm install
RUN npm run build

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer dump-autoload --optimize && \
	composer run-script post-install-cmd

# ECR image scaning doesn't currently support Alpine 3.12, so we need to stick
# to 3.11.
# https://github.com/aws/containers-roadmap/issues/963
FROM php:7.4-fpm-alpine3.11

# non root user to run the server after binding sockets as root
ENV NGINX_DOC_ROOT /var/www/html/web

ENV DRUPAL_CONSOLE_VERSION 1.8.0
ENV DRUSH_VERSION          0.6.0
ENV NGINX_VERSION          1.16.1-r6
ENV SUPERVISOR_VERSION     4.1.0-r0
ENV ZLIB_DEV_VERSION       1.2.11-r3
ENV LIBPNG_DEV_VERSION     1.6.37-r1
ENV LIBZIP_VERSION         1.5.2-r0

WORKDIR /var/www/html

RUN apk add --update --no-cache \
	nginx=$NGINX_VERSION \
	supervisor=$SUPERVISOR_VERSION\
	zlib-dev=$ZLIB_DEV_VERSION \
	libpng-dev=$LIBPNG_DEV_VERSION \
        libzip-dev=$LIBZIP_VERSION && \
	rm -rf /var/cache/apk/*

RUN docker-php-ext-install -j "$(nproc)" \
	gd \
	opcache \
	pdo \
	pdo_mysql \
	zip

COPY docker/php/php.ini /usr/local/etc/php
COPY docker/supervisord.conf /etc/
COPY docker/nginx.conf /etc/nginx/

RUN curl https://github.com/hechoendrupal/drupal-console-launcher/releases/download/$DRUPAL_CONSOLE_VERSION/drupal.phar -L -o drupal.phar
RUN mv drupal.phar /usr/local/bin/drupal
RUN chmod +x /usr/local/bin/drupal

RUN curl https://github.com/drush-ops/drush-launcher/releases/download/$DRUSH_VERSION/drush.phar -L -o drush.phar
RUN mv drush.phar /usr/local/bin/drush
RUN chmod +x /usr/local/bin/drush

# copy in the files that the builder image built for drupal
COPY --chown=www-data:www-data --from=builder /var/www/html /var/www/html

EXPOSE 80

STOPSIGNAL SIGTERM

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
