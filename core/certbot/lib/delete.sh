#!/bin/bash

# Recuperation des variables
fqdn=$1

# Protection des arguments
if [ "$#" -ne 1 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Suppression du certificat
	rm /etc/letsencrypt/live/$fqdn -r
	rm /etc/letsencrypt/renewal/$fqdn.conf
	rm /etc/letsencrypt/archive/$fqdn -r

	# Suppression du vhost
	rm /etc/apache2/sites-enabled/$fqdn-le-ssl.conf
	rm /etc/apache2/sites-available/$fqdn-le-ssl.conf

	# Suppression de la redirection du Vhost
	sed -i -e "s/RewriteEngine on//g" /etc/apache2/sites-available/$fqdn.conf
	sed -i -e "s/RewriteCond %{SERVER_NAME} =$fqdn//g" /etc/apache2/sites-available/$fqdn.conf
	sed "s/RewriteRule \^ https:\/\/\%{SERVER_NAME}\%{REQUEST_URI} \[END,NE,R=permanent\]//g" /etc/apache2/sites-available/$fqdn.conf

	# Redemarrage du service WEB
	service apache2 reload

fi
