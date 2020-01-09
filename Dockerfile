FROM victorhbfernandes/php-fpm-oracle-nginx

RUN rm -f /var/www/html/index.html
ADD as /var/www/html

EXPOSE 80

