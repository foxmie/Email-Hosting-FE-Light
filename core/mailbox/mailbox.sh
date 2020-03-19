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

	# Creation d'une boite mail
	if [ $action = "create" ];then
		echo -e "\e[42m Creation d'une boite mail \e[49m \n"
		bash "$emp/lib/create.sh" "$2" "$3"

	# Modification d'une boite mail
	elif [ $action = "set" ];then
		echo -e "\e[42m Modification d'une boite mail \e[49m \n"
                bash "$emp/lib/set.sh" "$2" "$3"

	# Activation d'une boite mail
	elif [ $action = "enable" ];then
		echo -e "\e[42m Activation d'une boite mail \e[49m \n"
                bash "$emp/lib/enable.sh" "$2"

	# Desactivation d'une boite mail
	elif [ $action = "disable" ];then
		echo -e "\e[42m Desactivation d'une boite mail \e[49m \n"
                bash "$emp/lib/disable.sh" "$2"

	# Suppression d'une boite mail
	elif [ $action = "delete" ];then
		echo -e "\e[42m Suppression d'une boite mail \e[49m \n"
		bash "$emp/lib/delete.sh" "$2"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
