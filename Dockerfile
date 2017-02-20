FROM php:5.6.30-apache

#VOLUME ["/home/chris/proj/salic-docker/teste" : "/var/www"]

RUN echo "[ Step 01 ] - Copying files to Image ***** ***** ***** "
COPY ./src /tmp/src

RUN echo "[ Step 02 ] - Installing PHP Dependencies ***** ***** ***** "
RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev && \
    docker-php-ext-install mbstring && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/  &&  \
    docker-php-ext-install gd
    docker-php-ext-install curl
    docker-php-ext-install soap
    docker-php-ext-install mssql
    docker-php-ext-install pear
    docker-php-ext-install pdo
    docker-php-ext-install xml

RUN chmod +x -R /tmp/src/

EXPOSE 80

RUN echo "[ Step 03 ] - Begin of Actions inside Image ***** ***** ***** "
CMD /tmp/src/actions/start.sh
