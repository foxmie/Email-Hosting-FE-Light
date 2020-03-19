#!/bin/bash

# Formatage du terminal
clear
echo -e "\e[44m Email Hosting FE Light \e[49m \n"

# Recuperation des arguments
lib=$1

# Verification de l'argument
if [ "$#" -lt 1 ];then

	echo -e "\e[104m Domaine \e[49m"
        cat "$PWD/domaine/lib/man.txt"
	echo -e "\e[104m Certbot \e[49m"
	cat "$PWD/certbot/lib/man.txt"
	echo -e "\e[104m MailBox \e[49m"
	cat "$PWD/mailbox/lib/man.txt"
	echo -e "\e[104m Alias \e[49m"
	cat "$PWD/alias/lib/man.txt"

else

	# Domaine
	if [ $lib = "domaine" ];then
		echo -e "\e[42m Domaine \e[49m \n"
		bash "$PWD/domaine/domaine.sh" "$2" "$3" "$4" "$5"

	# Certbot
	elif [ $lib = "certbot" ];then
		echo -e "\e[42m Certbot \e[49m \n"
		bash "$PWD/certbot/certbot.sh" "$2" "$3" "$4"

	# MailBox
        elif [ $lib = "mailbox" ];then
                echo -e "\e[42m MailBox \e[49m \n"
                bash "$PWD/mailbox/mailbox.sh" "$2" "$3" "$4" "$5"

	# Alias
        elif [ $lib = "alias" ];then
                echo -e "\e[42m Alias \e[49m \n"
                bash "$PWD/alias/alias.sh" "$2" "$3" "$4" "$5" "$6"

	# Services
        elif [ $lib = "services" ];then
                echo -e "\e[42m Services \e[49m \n"
                bash "$PWD/services/services.sh" "$2"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
