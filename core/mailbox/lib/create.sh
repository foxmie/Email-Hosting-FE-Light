#!/bin/bash

# Recuperation des variables
mailbox=$1
passwd=$2
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 2 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Creation de la boite mail
	mysql -uroot -p$mysqlroot -e "INSERT INTO postfix.addresses (id, active, email, pwd) VALUES ('', '1', '$mailbox', '');"

	# Generation du mot de passe
	passwd=$(doveadm pw -s SHA512-CRYPT -p "$passwd")
	passwd=${passwd:14}
	mysql -uroot -p$mysqlroot -e "UPDATE postfix.addresses SET pwd = '$passwd' WHERE email = '$mailbox';"

fi
