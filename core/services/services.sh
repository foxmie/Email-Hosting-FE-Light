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

	# Redemarrage du service dovecot
	elif [ $service = "dovecot" ];then
		echo -e "\e[42m Redemarrage du service de reception \e[49m \n"
		dovecot reload
		service dovecot restart

	# Redemarrage du service postfix
	elif [ $service = "postfix" ];then
		echo -e "\e[42m Redemarrage du service d'envoi \e[49m \n"
		postfix reload
		service postfix restart

	# Redemarrage du service fail2ban
	elif [ $service = "fail2ban" ];then
		echo -e "\e[42m Redemarrage du service  \e[49m \n"
		service fail2ban restart

	# Redemarrage du service spamassassin
        elif [ $service = "spamassassin" ];then
                echo -e "\e[42m Redemarrage du service  \e[49m \n"
		service spamassassin restart

	# Redemarrage du service ClamAV
        elif [ $service = "clamav" ];then
                echo -e "\e[42m Redemarrage du service  \e[49m \n"
		service clamav-daemon restart
		service clamsmtp restart

	# Redemarrage des services iptables
	elif [ $service = "iptables" ];then
		echo -e "\e[42m Redemarrage du service d'envoi \e[49m \n"
		service netfilter-persistent restart

	# Commande inconnue
	else
		echo -e "\e[101m Service inconnue \e[49m \n"
	fi

fi
