#!/bin/bash

# Formatage du terminal
clear
echo -e "\e[44m Services \e[49m \n"

# Recuperation des arguments
service=$1

# Verification de l'argument
if [ "$#" -lt 1 ]; then
	echo -e "\e[101m Il manque le service \e[49m \n"
else

	# Redemarrage du service WEB
	if [ $service = "web" ];then
		echo -e "\e[42m Redemarrage du service WEB \e[49m \n"
		service apache2 restart

	# Redemarrage du service de Base de Donnees
	elif [ $service = "mariadb" ];then
		echo -e "\e[42m Redemarrage du service de Base de Donnees \e[49m \n"
		service mariadb-server restart

	# Redemarrage du service de reception
	elif [ $service = "reception" ];then
		echo -e "\e[42m Redemarrage du service de reception \e[49m \n"

	# Redemarrage du service d'envoi
	elif [ $service = "envoi" ];then
		echo -e "\e[42m Redemarrage du service d'envoi \e[49m \n"

	# Redemarrage des services de protection
	elif [ $service = "security" ];then
		echo -e "\e[42m Redemarrage du service d'envoi \e[49m \n"

	# Commande inconnue
	else
		echo -e "\e[101m Service inconnue \e[49m \n"
	fi

fi
