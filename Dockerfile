
FROM php:5.3-apache

#VOLUME ["/home/chris/proj/salic-docker/teste" : "/var/www"]

COPY ./changehosts.sh /changehosts.sh
COPY ./run.sh /run.sh

RUN chmod +x /changehosts.sh
RUN chmod +x /run.sh

EXPOSE 80

CMD /run.sh
