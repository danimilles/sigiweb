FROM php:5.6-apache

RUN rm -f /var/www/html/index.html
ADD as /var/www/html

