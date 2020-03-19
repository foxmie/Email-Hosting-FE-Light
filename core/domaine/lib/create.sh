#!/bin/bash

# Recuperation des variables
domaine=$1
sousdomaine=$2
fqdn="$sousdomaine.$domaine"
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 2 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Ajout du domaine dans la base de donnees
	mysql -uroot -p$mysqlroot -e "INSERT INTO postfix.virtual_domains (id, name) VALUES ('', '$domaine');"

	# Creation du Vhost
	cat <<EOF >/etc/apache2/sites-available/$fqdn.conf

	Listen 80
	Listen 443

	<VirtualHost $fqdn>

	        DocumentRoot /var/lib/roundcube

	        ErrorLog /var/log/apache2/error.log
	        CustomLog /var/log/apache2/access.log combined

		<Directory "/var/lib/roundcube/">
			Options FollowSymLinks
			AllowOverride All
			Order allow,deny
			Allow from all
		</Directory>

	</VirtualHost>

	EOF

	# Activation du Vhost
	a2ensite $fqdn

	# Redemarrage du service WEB
	systemctl reload apache2

fi
