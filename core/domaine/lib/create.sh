#!/bin/bash

# Recuperation des variables
domaine=$1
sousdomaine=$2
fqdn="$sousdomaine.$domaine"
mysqlroot='mysql1234'

echo $fqdn

# Protection des arguments
if [ "$#" -ne 2 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Ajout du domaine dans la base de donnees
	mysql -uroot -p$mysqlroot -e "INSERT INTO postfix.virtual_domains (id, name) VALUES ('', '$domaine');"

	# Creation du Vhost
	echo "Listen 80" > /etc/apache2/sites-available/$fqdn.conf
	echo "Listen 443" >> /etc/apache2/sites-available/$fqdn.conf
	echo "" >> /etc/apache2/sites-available/$fqdn.conf
	echo "<VirtualHost $fqdn>" >> /etc/apache2/sites-available/$fqdn.conf
	echo "" >> /etc/apache2/sites-available/$fqdn.conf
	echo "	        DocumentRoot /var/lib/roundcube" >> /etc/apache2/sites-available/$fqdn.conf
	echo "" >> /etc/apache2/sites-available/$fqdn.conf
	echo "	        ErrorLog /var/log/apache2/error.log" >> /etc/apache2/sites-available/$fqdn.conf
	echo "	        CustomLog /var/log/apache2/access.log combined" >> /etc/apache2/sites-available/$fqdn.conf
	echo "" >> /etc/apache2/sites-available/$fqdn.conf
	echo '		<Directory "/var/lib/roundcube/">' >> /etc/apache2/sites-available/$fqdn.conf
	echo "			Options FollowSymLinks" >> /etc/apache2/sites-available/$fqdn.conf
	echo "			AllowOverride All" >> /etc/apache2/sites-available/$fqdn.conf
	echo "			Order allow,deny" >> /etc/apache2/sites-available/$fqdn.conf
	echo "			Allow from all" >> /etc/apache2/sites-available/$fqdn.conf
	echo "		</Directory>" >> /etc/apache2/sites-available/$fqdn.conf
	echo "" >> /etc/apache2/sites-available/$fqdn.conf
	echo "</VirtualHost>" >> /etc/apache2/sites-available/$fqdn.conf

	# Activation du Vhost
	a2ensite $fqdn

	# Redemarrage du service WEB
	systemctl reload apache2

fi
