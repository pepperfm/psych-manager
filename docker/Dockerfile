FROM phpdockerio/php74-fpm:latest
#FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        openssl \
        curl \
        wget \
        git \
        unzip \
        libpq-dev \
    --no-install-recommends

#RUN apt-get update \
#&& docker-php-ext-install pdo pdo_mysql gd

RUN apt-get update \
    && apt-get -y --no-install-recommends install mysql-server mysql-client php7.4-mysql php7.4-gd php7.4-pdo php7.4-pdo-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

#RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
#&& curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \

#&& php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
#&& php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot \
#&& rm -f /tmp/composer-setup.*
#RUN composer --version
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#EXPOSE 9000
