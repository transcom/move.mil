FROM php:7.1-apache

RUN apt-get update && apt-get install -y gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        build-essential \
        git \
        nodejs \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /root
ENV DART_SASS_VERSION 1.7.0
RUN curl -L --remote-name https://github.com/sass/dart-sass/releases/download/$DART_SASS_VERSION/dart-sass-$DART_SASS_VERSION-linux-x64.tar.gz
RUN tar xzf dart-sass-$DART_SASS_VERSION-linux-x64.tar.gz && rm dart-sass-$DART_SASS_VERSION-linux-x64.tar.gz

ENV PATH="/root/dart-sass:$PATH"
ENV APACHE_DOCUMENT_ROOT /var/www/html/web

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install

WORKDIR /var/www/html/web/themes/custom/move_mil/
RUN npm install && npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools/react-weight-estimator/src/sass
RUN sass main.scss ../localcss/main.css
RUN npm install && npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools/react-locator-map/src/sass
RUN sass main.scss ../localcss/main.css
RUN npm install && npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools/react-ppm-tool/src/sass
RUN sass main.scss ../localcss/main.css
RUN npm install && npm run build

WORKDIR /var/www/html/web/modules/custom/react_tools/tools/react-entitlements-page/src/sass
RUN sass main.scss ../localcss/main.css
RUN npm install && npm run build

EXPOSE 80