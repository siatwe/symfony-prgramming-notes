FROM alpine:3.8

RUN apk add --update --no-cache \
    git \
    php7 \
    php7-apcu \
    php7-bcmath \
    php7-common \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-exif \
    php7-amqp \
    php7-fileinfo \
    php7-fpm \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-intl \
    php7-json \
    php7-sockets \
    php7-mbstring \
    php7-mcrypt \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-pdo_sqlite \
    php7-phar \
    php7-posix \
    php7-session \
    php7-xdebug \
    php7-simplexml \
    php7-tokenizer \
    php7-xml \
    php7-xmlwriter \
    php7-zip \
    php7-zlib \
    openssh

ADD php-fpm.conf /etc/php7/php-fpm.conf
ADD www.conf /etc/php7/php-fpm.d/www.conf
ADD https://getcomposer.org/download/1.6.5/composer.phar /usr/local/bin/composer
ADD xdebug.ini /etc/php7/conf.d/xdebug.ini

RUN addgroup -g 1000 www \
    && adduser -D -u 1000 -G www www \
    && ln -s /usr/bin/php7 /usr/local/bin/php \
    && chmod 0755 /usr/local/bin/composer

WORKDIR /opt/project

ENV COMPOSER_MEMORY_LIMIT=-1

USER www
EXPOSE 9000
CMD ["php-fpm7", "-F"]
