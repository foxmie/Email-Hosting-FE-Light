#!/bin/bash

#Recuperation des variables
id=$1
source=$2
target=$3
mysqlroot='mysql1234'

# Protection des arguments
if [ "$#" -ne 3 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Modification de l'alias
	mysql -uroot -p$mysqlroot -e "UPDATE postfix.aliases SET source = '$source', target = '$target' WHERE id = '$id';"

fi
