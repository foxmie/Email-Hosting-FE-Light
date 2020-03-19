#!/bin/bash

# Formatage du terminal
clear
echo -e "\e[44m Certbot \e[49m \n"

# Recuperation des arguments
action=$1

# Recuperation de l'arborescence
emp=$PWD'/certbot'

# Verification de l'argument
if [ "$#" -lt 1 ]; then
	cat "$emp/lib/man.txt"
else

	# Creation du certificat SSL Let's Encrypt
	if [ $action = "create" ];then
		echo -e "\e[42m Creation d'un certificat SSL Let's Encrypt \e[49m \n"
		bash "$emp/lib/create.sh" "$2" "$3"

	# Suppression du certificat SSL Let's Encrypt
	elif [ $action = "delete" ];then
		echo -e "\e[42m Suppression d'un certificat SSL Let's Encrypt \e[49m \n"
		bash "$emp/lib/delete.sh" "$2" "$3"

	# Renouvellement des certificats SSL Let's Encrypt
	elif [ $action = "renew" ];then
		echo -e "\e[42m Renouvellement des certificats SSL Let's Encrypt \e[49m \n"
		bash "$emp/lib/renew.sh"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
