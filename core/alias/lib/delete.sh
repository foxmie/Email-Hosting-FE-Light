#!/bin/bash

# Recuperation des variables
id=$1
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 1 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Suppression de l'alias
	mysql -uroot -p$mysqlroot -e "DELETE FROM postfix.aliases WHERE id = '$id';"

fi
