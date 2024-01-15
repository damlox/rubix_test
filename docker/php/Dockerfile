FROM php:8.1.6-fpm

ARG TIMEZONE
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get -y update && apt-get upgrade -y

RUN apt-get -y update \
    && apt-get upgrade -y \
    && apt-get -y install --fix-missing \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    wget \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    nodejs \
    python2 \
    build-essential \
    libpq-dev \
    gfortran \
    libopenblas-dev \
    liblapacke-dev

RUN docker-php-ext-configure gd

RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql zip xsl gd intl opcache exif mbstring soap

RUN pecl install xdebug
RUN pecl install svm-beta
RUN pecl install tensor
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-enable tensor

RUN echo "[xdebug]\n\
zend_extension=xdebug.so\n\
xdebug.client_host=host.docker.internal\n\
xdebug.client_port=9003\n\
xdebug.idekey=PHPSTORM\n\
xdebug.mode=debug" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --version=2.3.5 --install-dir=/usr/local/bin --filename=composer
RUN wget https://get.symfony.com/cli/installer -O - | bash

RUN mkdir -p /var/www

RUN echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.max_children = 50' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.max_requests = 500' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.start_servers = 5' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.min_spare_servers = 5' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.max_spare_servers = 10' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.process_idle_timeout = 10s;' >> /usr/local/etc/php-fpm.d/zz-docker.conf

RUN echo 'extension=/usr/local/lib/php/extensions/no-debug-non-zts-20210902/svm.so' > /usr/local/etc/php/conf.d/docker-php-ext-svm.ini

RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data \
    && chown -R www-data:www-data /var/www

USER www-data
WORKDIR /var/www/html
