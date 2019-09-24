FROM php:5.6-apache

ENV APACHE_DOCUMENT_ROOT /var/www/app/public

RUN apt-get update && apt-get install -y libicu-dev && docker-php-ext-install -j$(nproc) intl
RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN echo 'date.timezone=UTC' > "$PHP_INI_DIR/conf.d/datetime.ini"
RUN echo 'xdebug.remote_enable=on' > "$PHP_INI_DIR/conf.d/ext-xdebug.ini"
RUN echo 'xdebug.remote_connect_back=on' >> "$PHP_INI_DIR/conf.d/ext-xdebug.ini"
