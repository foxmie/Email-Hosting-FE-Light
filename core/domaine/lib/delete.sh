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

	# Suppression des comptes attaches au domaine
	mysql -uroot -p$mysqlroot -e "DELETE FROM postfix.addresses WHERE email like '%@$domaine'"

	# Suppression des alias attaches au domaine
	mysql -uroot -p$mysqlroot -e "DELETE FROM postfix.aliases WHERE source like '%@$domaine'"

	# Suppression du domaine
	mysql -uroot -p$mysqlroot -e "DELETE FROM postfix.virtual_domains WHERE name = '$domaine';"

	# Suppression du dossier conteneur du domaine
	rm -r /var/mail/vmail/$domaine

	# Suppression du Vhost
	rm /etc/apache2/sites-enabled/$fqdn.conf
	rm /etc/apache2/sites-available/$fqdn.conf

	# Redemarrage du service WEB
	service apache2 restart

fi
