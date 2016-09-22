FROM culturagovbr/web-apache-php5-debian
MAINTAINER ruyjfs@gmail.com
RUN apt-get clean
#CMD ["/bin/bash", "apachectl", "-DFOREGROUND"]
#CMD ["/bin/bash"]
#ENTRYPOINT ["apachectl", "start"]

#CMD ["/bin/bash"]
#CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
VOLUME ["/var/www" : "/var/www", "/var/log/apache2":"/var/log/apache2"]
CMD ["/bin/bash"]
#ENTRYPOINT ["service", "apache2", "start", "-D", "FOREGROUND"]
ENTRYPOINT ["apachectl", "-D", "FOREGROUND"]
#apachectl -D FOREGROUND

EXPOSE 80:80