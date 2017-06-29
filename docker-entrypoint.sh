#!/bin/bash
set -e
echo "[ ****************** ] Starting Endpoint of Application"
if ! [ -d "/var/www/salic-br" ] || ! [ -d "/var/www/salic-br/application" ]; then
    echo "Application not found in /var/www/salic-br - cloning now..."
    if [ "$(ls -A)" ]; then
        echo "WARNING: /var/www/salic-br is not empty - press Ctrl+C now if this is an error!"
        ( set -x; ls -A; sleep 10 )
    fi
    echo "[ ****************** ] Cloning Project repository to tmp folder"
    git clone -b 'develop' http://git.cultura.gov.br/sistemas/novo-salic.git /tmp/salic-br
    ls -la /tmp/salic-br

    echo "[ ****************** ] Copying Project from temporary folder to workdir"
    cp /tmp/salic-br -r /var/www

    echo "[ ****************** ] Copying sample application configuration to real one"
    cp /var/www/salic-br/application/configs/exemplo-application.ini /var/www/salic-br/application/configs/application.ini

    echo "[ ****************** ] Changing owner and group from the Project to 'www-data' "
    chown www-data:www-data -R /var/www/salic-br

    ls -la /var/www/salic-br

    echo "Complete! The application has been successfully copied to /var/www/salic-br"
fi
echo "[ ****************** ] Ending Endpoint of Application"
exec "$@"