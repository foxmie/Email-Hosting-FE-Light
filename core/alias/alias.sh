#!/bin/bash

# Formatage du terminal
clear
echo -e "\e[44m Alias \e[49m \n"

# Recuperation des arguments
action=$1

# Recuperation de l'arborescence
emp=$PWD'/alias'

# Verification de l'argument
if [ "$#" -lt 1 ]; then
	cat "$emp/lib/man.txt"
else

	# Creation d'un alias
	if [ $action = "create" ];then
		echo -e "\e[42m Creation d'un alias \e[49m \n"
		bash "$emp/lib/create.sh" "$2" "$3"

	# Modification d'un alias
	elif [ $action = "set" ];then
		echo -e "\e[42m Modification d'un alias \e[49m \n"
		bash "$emp/lib/set.sh" "$2" "$3" "$4"

	# Suppression d'un alias
	elif [ $action = "delete" ];then
		echo -e "\e[42m Suppression d'un alias \e[49m \n"
		bash "$emp/lib/delete.sh" "$2"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
