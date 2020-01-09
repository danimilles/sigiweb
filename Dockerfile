FROM donvito/php-oci8

RUN rm -f /var/www/html/index.html
ADD as /var/www/html

EXPOSE 80

