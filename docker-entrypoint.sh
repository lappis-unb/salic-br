#!/bin/bash
set -e
echo "[ ****************** ] Starting Endpoint of Application"
if ! [ -d "/var/www/salic-br" ] || ! [ -d "/var/www/salic-br/application" ]; then
    echo "Application not found in /var/www/salic-br - cloning now..."
    if [ "$(ls -A /var/www/salic-br)" ]; then
        echo "WARNING: /var/www/salic-br is not empty - press Ctrl+C now if this is an error!"
        ( set -x; ls -A; sleep 5 )
    fi
    echo "[ ****************** ] Cloning Project repository to tmp folder"
    #rm -Rf /tmp/salic-br
    git clone -b 'develop' https://github.com/culturagovbr/salic-br /tmp/salic-br
    ls -la /tmp/salic-br

    echo "[ ****************** ] Copying Project from temporary folder to workdir"
 #    cp /tmp/salic-br -rT /var/www/salic-br
    cp /tmp/salic-br -R /var/www/

    echo "[ ****************** ] Copying sample application configuration to real one"
    cp /var/www/salic-br/application/configs/exemplo-application.ini /var/www/salic-br/application/configs/application.ini

    echo "[ ****************** ] Changing owner and group from the Project to 'www-data' "
    chown www-data:www-data -R /var/www/salic-br

    ls -la /var/www/salic-br


    echo "[ ****************** ] Downloading composer "

    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

    echo "[ ****************** ] Installing composer "
    php composer-setup.php

    echo "[ ****************** ] Unlinking and moving composer to '/usr/local/bin/' directory"
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer


    cd /var/www/salic-br
    composer update
    echo "Complete! The application has been successfully copied to /var/www/salic-br"
fi

# X-Debug

if ! [ -v $XDEBUG_REMOTE_ENABLE ] && [ "$XDEBUG_INSTALL" = "true" ] ; then
	pecl shell-test xdebug && echo "Package xdebug Installed" || (
	    echo "[ ****************** ] Starting install of XDebug and dependencies."
	    yes | pecl install xdebug
	    echo "zend_extension="`find /usr/local/lib/php/extensions/ -iname 'xdebug.so'` > $XDEBUGINI_PATH
	    echo "xdebug.remote_enable=$XDEBUG_REMOTE_ENABLE" >> $XDEBUGINI_PATH

	    if ! [ -v $XDEBUG_REMOTE_AUTOSTART ] ; then
	        echo "xdebug.remote_autostart=$XDEBUG_REMOTE_AUTOSTART" >> $XDEBUGINI_PATH
	    fi
	    if ! [ -v $XDEBUG_REMOTE_CONNECT_BACK ] ; then
	        echo "xdebug.remote_connect_back=$XDEBUG_REMOTE_CONNECT_BACK" >> $XDEBUGINI_PATH
	    fi
	    if ! [ -v $XDEBUG_REMOTE_HANDLER ] ; then
	        echo "xdebug.remote_handler=$XDEBUG_REMOTE_HANDLER" >> $XDEBUGINI_PATH
	    fi
	    if ! [ -v $XDEBUG_PROFILER_ENABLE ] ; then
	        echo "xdebug.profiler_enable=$XDEBUG_PROFILER_ENABLE" >> $XDEBUGINI_PATH
	    fi
	    if ! [ -v $XDEBUG_PROFILER_OUTPUT_DIR ] ; then
	        echo "xdebug.profiler_output_dir=$XDEBUG_PROFILER_OUTPUT_DIR" >> $XDEBUGINI_PATH
	    fi
	    if ! [ -v $XDEBUG_REMOTE_PORT ] ; then
	        echo "xdebug.remote_port=$XDEBUG_REMOTE_PORT" >> $XDEBUGINI_PATH
	    fi

	    if ! [ -v $XDEBUG_DEFAULT_ENABLE ] ; then
            echo "xdebug.default_enable=$XDEBUG_DEFAULT_ENABLE" >> $XDEBUGINI_PATH
        fi
        if ! [ -v $XDEBUG_IDEKEY ] ; then
            echo "xdebug.idekey=$XDEBUG_IDEKEY" >> $XDEBUGINI_PATH
        fi

	    echo "xdebug.remote_host="`/sbin/ip route|awk '/default/ { print $3 }'` >> $XDEBUGINI_PATH
	    echo "[ ****************** ] Ending install of XDebug and dependencies."

	)
fi

echo "[ ****************** ] Ending Endpoint of Application"
exec "$@"
