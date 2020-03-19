#!/bin/bash

# Recuperation des variables
source=$1
target=$2
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 2 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Creation de l'alias
	mysql -uroot -p$mysqlroot -e "INSERT INTO postfix.aliases (id, source, target) VALUES ('', '$source', '$target');"

fi
