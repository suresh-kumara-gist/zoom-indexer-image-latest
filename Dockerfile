FROM php:7.3-rc-apache
RUN apt-get update -y\
    && apt-get install libwpd-tools -y
COPY env/zoom.conf /etc/apache2/sites-available/zoom.conf
COPY app/zoom /var/www/html/zoom
RUN mkdir /zoomindexer-output

EXPOSE 80
ENTRYPOINT "apache2-foreground"
