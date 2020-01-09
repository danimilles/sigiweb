FROM ramonfdlr/sigi_base

RUN rm -f /var/www/html/index.html
ADD as /var/www/html

