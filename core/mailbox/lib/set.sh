#!/bin/bash

# Recuperation des variables
mailbox=$1
passwd=$2
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 2 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Modification du mot de passe de la boite mail
	passwd=$(doveadm pw -s SHA512-CRYPT -p "$passwd")
	passwd=${passwd:14}
	mysql -uroot -p$mysqlroot -e "UPDATE postfix.addresses SET pwd = '$passwd' WHERE email = '$mailbox';"

fi
