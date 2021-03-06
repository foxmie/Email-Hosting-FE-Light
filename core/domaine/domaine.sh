#!/bin/bash

# Formatage du terminal
clear
echo -e "\e[44m Domaine \e[49m \n"

# Recuperation des arguments
action=$1

# Recuperation de l'arborescence
emp=$PWD'/domaine'

# Verification de l'argument
if [ "$#" -lt 1 ]; then
	cat "$emp/lib/man.txt"
else

	# Creation du domaine
	if [ $action = "create" ];then
		echo -e "\e[42m Creation d'un domaine \e[49m \n"
		bash "$emp/lib/create.sh" "$2" "$3"

	# Suppression du domaine
	elif [ $action = "delete" ];then
		echo -e "\e[42m Suppression d'un domaine \e[49m \n"
		bash "$emp/lib/delete.sh" "$2" "$3"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
