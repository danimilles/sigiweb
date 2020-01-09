FROM caffeina/php-fpm-oci8

RUN rm -f /var/www/html/index.html
ADD as /var/www/html

EXPOSE 80

