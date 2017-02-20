
echo "[******] Copying and enable virtualhost 'salic.conf'";
cp ./src/actions/virtual-host/salic.conf /etc/apache2/sites-available/salic.conf


echo "[******] Changing Apache Enviroment Variable";
echo "Is Production? (S/n)"
read isProduction
if [ "$isProduction" = "S" ] || [ "$isProduction" = "s" ] || [ "$isProduction" = "" ]
then
    sed -i \"s/development/production/g\" /etc/apache2/sites-available/salic.conf
fi

a2ensite local.salic.conf
a2ensite salic.conf

# Disable default virtualhost '000-default.conf'
a2dissite 000-default.conf

# Starts Apache using Foreground Mode
apache2ctl -D FOREGROUND