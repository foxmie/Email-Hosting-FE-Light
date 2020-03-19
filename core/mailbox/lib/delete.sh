#!/bin/bash

# Recuperation des variables
mailbox=$1
user=$(echo $1 | cut -f1 -d@)
domaine=$(echo $1 | cut -f2 -d@)
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 1 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Suppression de la boite mail
	mysql -uroot -p$mysqlroot -e "DELETE FROM postfix.addresses WHERE email = '$mailbox';"

	# Suppression des mails de la boite mail
	rm -r /var/mail/vmail/$domaine/$user

fi
