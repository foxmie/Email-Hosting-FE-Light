#!/bin/bash

# Recuperation des variables
fqdn=$1
home='/var/lib/roundcube/'

# Protection des arguments
if [ "$#" -ne 1 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Installation du certificat SSL Let's Encrypt
	certbot run -a webroot -i apache -w $home -d $fqdn --register-unsafely-without-email --redirect --agree-tos --force-renewal

	# Redemarrage du service WEB
	service apache2 reload

fi
