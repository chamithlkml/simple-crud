FROM php:7.4-apache

RUN mkdir -p /var/www/html
WORKDIR /var/www/html
COPY . .

RUN apt-get update
RUN apt-get install -y libicu-dev
RUN docker-php-ext-install intl mysqli pdo
RUN a2dissite 000-default
RUN a2enmod rewrite
COPY docker/apache/cf_partners.conf /etc/apache2/sites-enabled/cf_partners.conf
RUN chown -R www-data:www-data /var/www/html/public
RUN chmod -R 755 /var/www/html/public

EXPOSE 80