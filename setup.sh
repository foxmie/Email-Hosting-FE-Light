#!/bin/bash

# Verification de la version de debian
if [ -f "/etc/debian_version" ];then
	echo "Le fichier de version est charge !";
	var=`cat /etc/debian_version`
	var=`echo $var | cut -f1 -d.`
	if [ $var = 9 ];then
		echo "La version de debian est compatible !";
	else
		echo "Script pour installation sur Debian 9 uniquement!";
		exit 2
	fi
else
	echo "Script pour installation sur Debian uniquement!";
	exit 1
fi

# Generation des variables
hostname=`hostname`
mailmanpwd='Azerty1234!'
roundcubepass='Azerty123!'
mysqlroot='mysql1234'
emailhostingfepassword=`date +%s | sha256sum | base64 | head -c 25`

# Mise a jour du serveur
apt-get update && apt-get -y upgrade && apt-get -y dist-upgrade && apt-get -y full-upgrade

# Installation des utilitaires
apt-get -y install tmux
apt-get -y install unzip zip
apt-get -y install telnet
apt-get -y install ntp ntpdate
apt-get -y install quota quotatool
apt-get -y install debconf-utils

# Configuration de l'horloge
service ntp stop
rm /etc/timezone
rm /etc/localtime
echo "Europe/Paris" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata
ntpdate pool.ntp.org
service ntp start

# Configuration de ntp
sed -i -e "s/pool 0.debian.pool.ntp.org iburst/server 0.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf
sed -i -e "s/pool 1.debian.pool.ntp.org iburst/server 1.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf
sed -i -e "s/pool 2.debian.pool.ntp.org iburst/server 2.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf
sed -i -e "s/pool 3.debian.pool.ntp.org iburst/server 3.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf
/etc/init.d/ntp restart

# Installation des services WEB
apt-get -y install apache2
apt-get -y install python-certbot-apache

# Configuration d'apache2
sed -i "1iServerName $hostname" /etc/apache2/apache2.conf
sed -i 's/ServerTokens OS/ServerTokens Prod /g' /etc/apache2/conf-enabled/security.conf
a2enmod rewrite

# Redemarrage des services WEB
systemctl restart apache2

# Installation du serveur de base de donnees
apt-get -y install mariadb-server

# Installation du service SMTP
debconf-set-selections <<< "postfix postfix/mailname string $hostname"
debconf-set-selections <<< "postfix postfix/main_mailer_type string 'Internet Site'"
apt-get -y install postfix postfix-mysql

# Installation des services d'envoi de mail
apt-get -y install dovecot-core dovecot-imapd dovecot-lmtpd dovecot-managesieved dovecot-mysql dovecot-sieve
apt-get -y install php-net-sieve

# Creation de la base de donnees et de son utilisateur postfix
mysql -u root -e "CREATE DATABASE postfix;"
mysql -u root postfix < config/postfix.sql
mysql -u root -e "CREATE USER 'mailman'@'127.0.0.1' IDENTIFIED BY '$mailmanpwd';"
mysql -u root -e "GRANT SELECT ON postfix.addresses TO 'mailman'@'127.0.0.1';"
mysql -u root -e "GRANT SELECT ON postfix.aliases TO 'mailman'@'127.0.0.1';"
mysql -u root -e "GRANT SELECT ON postfix.virtual_domains TO 'mailman'@'127.0.0.1';"

# Installation des utilitaires de securite
apt-get -y install spamassassin spamc
apt-get -y install clamav-daemon clamav clamsmtp clamav-freshclam
apt-get -y install fail2ban
echo iptables-persistent iptables-persistent/autosave_v4 boolean true | sudo debconf-set-selections
echo iptables-persistent iptables-persistent/autosave_v6 boolean true | sudo debconf-set-selections
debconf-get-selections | grep iptables
apt-get -y install iptables-persistent

# Creation du repertoire des domaines
mkdir -p /var/mail/vmail
useradd -d /var/mail -U -u 5000 vmail
chown -R vmail:vmail /var/mail/vmail

# Creation de l'utilisateur spamd
adduser --gecos "" spamd --disabled-login

# Suppression des fichiers remplaces par la base de donnees
if [ -e /etc/postfix/virtual_mailbox_maps.cf ];then
	rm /etc/postfix/virtual_mailbox_maps.cf
fi
if [ -e /etc/postfix/virtual_mailbox_maps.cf.db ];then
	rm /etc/postfix/virtual_mailbox_maps.cf.db
fi

# Modification du mot de passe mailman
mysql -u root -e "SET PASSWORD FOR 'mailman'@'127.0.0.1' = PASSWORD('$mailmanpwd');"

# installation de roundcube
echo "roundcube-core roundcube/dbconfig-install boolean true" | debconf-set-selections
echo "roundcube-core roundcube/database-type select mysql" | debconf-set-selections
echo "roundcube-core roundcube/mysql/admin-pass password " | debconf-set-selections
echo "roundcube-core roundcube/db/dbname string roundcube" | debconf-set-selections
echo "roundcube-core roundcube/mysql/app-pass password $roundcubepass" | debconf-set-selections
echo "roundcube-core roundcube/app-password-confirm password $roundcubepass" | debconf-set-selections
echo "roundcube-core roundcube/hosts string localhost" | debconf-set-selections
apt-get -y install roundcube roundcube-plugins

# Sauvegarde des fichiers de configuration
mv /etc/postfix/main.cf /etc/postfix/main.cf.bak
mv /etc/postfix/master.cf /etc/postfix/master.cf.bak
mv /etc/postfix/mysql_virtual_mailbox_maps.cf /etc/postfix/mysql_virtual_mailbox_maps.cf.bak
mv /etc/postfix/mysql_virtual_aliases.cf /etc/postfix/mysql_virtual_aliases.cf.bak
mv /etc/dovecot/conf.d/10-logging.conf /etc/dovecot/conf.d/10-logging.conf.bak
mv /etc/dovecot/conf.d/10-mail.conf /etc/dovecot/conf.d/10-mail.conf.bak
mv /etc/dovecot/conf.d/10-auth.conf /etc/dovecot/conf.d/10-auth.conf.bak
mv /etc/dovecot/conf.d/10-master.conf /etc/dovecot/conf.d/10-master.conf.bak
mv /etc/dovecot/conf.d/15-mailboxes.conf /etc/dovecot/conf.d/15-mailboxes.conf.bak
mv /etc/dovecot/conf.d/15-lda.conf /etc/dovecot/conf.d/15-lda.conf.bak
mv /etc/dovecot/conf.d/90-sieve.conf /etc/dovecot/conf.d/90-sieve.conf.bak
mv /etc/dovecot/conf.d/90-quota.conf /etc/dovecot/conf.d/90-quota.conf.bak
mv /etc/dovecot/conf.d/20-imap.conf /etc/dovecot/conf.d/20-imap.conf.bak
mv /etc/dovecot/dovecot-sql.conf.ext /etc/dovecot/dovecot-sql.conf.ext.bak
mv /etc/roundcube/apache.conf /etc/roundcube/apache.conf.bak
mv /etc/roundcube/config.inc.php /etc/roundcube/config.inc.php.bak
mv /etc/roundcube/plugins/password/config.inc.php /etc/roundcube/plugins/password/config.inc.php.bak
mv /etc/fail2ban/filter.d/postfix.conf /etc/fail2ban/filter.d/postfix.conf.bak
mv /etc/fail2ban/filter.d/roundcube-auth.conf /etc/fail2ban/filter.d/roundcube-auth.conf.bak
mv /usr/share/roundcube/plugins/managesieve/config.inc.php /usr/share/roundcube/plugins/managesieve/config.inc.php.bak

# Importation des fichiers de configuration
mv config/postfix/main.cf /etc/postfix/main.cf
mv config/postfix/master.cf /etc/postfix/master.cf
mv config/postfix/mysql_virtual_mailbox_maps.cf /etc/postfix/mysql_virtual_mailbox_maps.cf
mv config/postfix/mysql-virtual-mailbox-domains.cf /etc/postfix/mysql-virtual-mailbox-domains.cf
mv config/postfix/mysql_virtual_aliases.cf /etc/postfix/mysql_virtual_aliases.cf
mv config/dovecot/conf.d/10-logging.conf /etc/dovecot/conf.d/10-logging.conf
mv config/dovecot/conf.d/10-mail.conf /etc/dovecot/conf.d/10-mail.conf
mv config/dovecot/conf.d/10-auth.conf /etc/dovecot/conf.d/10-auth.conf
mv config/dovecot/conf.d/10-master.conf /etc/dovecot/conf.d/10-master.conf
mv config/dovecot/conf.d/15-mailboxes.conf /etc/dovecot/conf.d/15-mailboxes.conf
mv config/dovecot/conf.d/15-lda.conf /etc/dovecot/conf.d/15-lda.conf
mv config/dovecot/conf.d/90-sieve.conf /etc/dovecot/conf.d/90-sieve.conf
mv config/dovecot/conf.d/90-quota.conf /etc/dovecot/conf.d/90-quota.conf
mv config/dovecot/conf.d/20-imap.conf /etc/dovecot/conf.d/20-imap.conf
mv config/dovecot/dovecot-sql.conf.ext /etc/dovecot/dovecot-sql.conf.ext
mv config/roundcube/apache.conf /etc/roundcube/apache.conf
mv config/roundcube/config.inc.php /etc/roundcube/config.inc.php
mv config/roundcube/plugins/password/config.inc.php /etc/roundcube/plugins/password/config.inc.php
mv config/fail2ban/jail.local /etc/fail2ban/jail.local
mv config/fail2ban/postfix.conf /etc/fail2ban/filter.d/postfix.conf
mv config/fail2ban/roundcube-auth.conf /etc/fail2ban/filter.d/roundcube-auth.conf
cp /usr/share/roundcube/plugins/managesieve/config.inc.php.dist /etc/roundcube/plugins/managesieve/config.inc.php

# Suppression du dossier config
rm -r config

# Configuration des fihciers de configuration
sed -i -e "s/password=mailman/password=$mailmanpwd/g" /etc/dovecot/dovecot-sql.conf.ext
sed -i -e "s/password = mailman/password = $mailmanpwd/g" /etc/postfix/mysql_virtual_mailbox_maps.cf
sed -i -e "s/password = mailman/password = $mailmanpwd/g" /etc/postfix/mysql_virtual_aliases.cf
sed -i -e "s/password = mailman/password = $mailmanpwd/g" /etc/postfix/mysql-virtual-mailbox-domains.cf
postconf virtual_mailbox_domains=mysql:/etc/postfix/mysql-virtual-mailbox-domains.cf

# Configuration de spamassassin
sed -i -e "s/CRON=0/CRON=1/g" /etc/default/spamassassin
sed -i -e "s/# report_safe 1/report_safe 0/g" /etc/spamassassin/local.cf
mkdir /etc/dovecot/sieve
echo "require ["fileinto"];" > /etc/dovecot/sieve/spamfilter.sieve
echo "# rule:[SPAM]" >> /etc/dovecot/sieve/spamfilter.sieve
echo "if header :contains "X-Spam-Level" "*" {" >> /etc/dovecot/sieve/spamfilter.sieve
echo "        fileinto "Junk";" >> /etc/dovecot/sieve/spamfilter.sieve
echo "}" >> /etc/dovecot/sieve/spamfilter.sieve

# Reglage des permissions
chown www-data:www-data /etc/roundcube/config.inc.php
chown www-data:www-data /etc/roundcube/plugins/password/config.inc.php

# Permettre a RoundCube de modifier le mot de passe des comptes
mysql -u root -e "GRANT SELECT (email), UPDATE (pwd) ON postfix.addresses TO 'roundcube'@'localhost';"

# Configuration de ClamAV
chown -R clamav:clamav /var/spool/clamsmtp/
chown -R clamav:clamav /var/run/clamsmtp/
sed -i -e "s/User: clamsmtp/User: clamav/g" /etc/clamsmtpd.conf

# Configuration de Postfix
echo "" >> /etc/postfix/main.cf
echo "# Virusscanner" >> /etc/postfix/main.cf
echo "content_filter = scan:127.0.0.1:10026" >> /etc/postfix/main.cf
echo "receive_override_options = no_address_mappings" >> /etc/postfix/main.cf

echo "" >> /etc/postfix/master.cf
echo "# Antivirus" >> /etc/postfix/master.cf
echo "scan      unix  -       -       n       -       16      smtp" >> /etc/postfix/master.cf
echo "        -o smtp_send_xforward_command=yes" >> /etc/postfix/master.cf
echo "" >> /etc/postfix/master.cf
echo "# For injecting mail back into postfix from the filter" >> /etc/postfix/master.cf
echo "127.0.0.1:10025 inet  n -       n       -       16      smtpd" >> /etc/postfix/master.cf
echo "        -o content_filter=" >> /etc/postfix/master.cf
echo "        -o receive_override_options=no_unknown_recipient_checks,no_header_body_checks" >> /etc/postfix/master.cf
echo "        -o smtpd_helo_restrictions=" >> /etc/postfix/master.cf
echo "        -o smtpd_client_restrictions=" >> /etc/postfix/master.cf
echo "        -o smtpd_sender_restrictions=" >> /etc/postfix/master.cf
echo "        -o smtpd_recipient_restrictions=permit_mynetworks,reject" >> /etc/postfix/master.cf
echo "        -o mynetworks_style=host" >> /etc/postfix/master.cf
echo "        -o smtpd_authorized_xforward_hosts=127.0.0.0/8" >> /etc/postfix/master.cf

# Configuration du mirroir ClamAV
sed -i -e "s/DatabaseMirror db.local.clamav.net/DatabaseMirror db.FR.clamav.net/g" /etc/clamav/freshclam.conf

# Configuration du mailname
echo $hostname > /etc/mailname

# Redemarrage des services
service spamassassin start
service apache2 reload
dovecot reload && postfix reload
service dovecot restart
service postfix restart
service spamassassin reload
service clamav-daemon start
service clamsmtp restart
service fail2ban reload

# mysql_secure_installation
mysql -uroot mysql -e "UPDATE mysql.user SET Password=PASSWORD('$mysqlroot') WHERE User='root';"
mysql -uroot mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -uroot mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host!='localhost'"
mysql -uroot mysql -e "DROP DATABASE test;"
mysql -uroot mysql -e "DELETE FROM mysql.db WHERE Db=’test’ OR Db=’test_%’"
mysql -uroot mysql -e "FLUSH PRIVILEGES"

# Modification du fichier fstab
sed -i 's/errors=remount-ro/errors=remount-ro,usrquota,grpquota/g' /etc/fstab
mount -a
mount -o remount /

# Initialisation des quotas
quotaoff -ugv /
quotacheck -ugm /
ls -l / | grep aquota
quotaon -ugv /
quotaon -pa

# Configuration du pare-feu
if [ -f /etc/iptables/rules.v6 ]; then
   echo "*filter" > /etc/iptables/rules.v6
   echo ":INPUT DROP [0:0]" >> /etc/iptables/rules.v6
   echo ":FORWARD DROP [0:0]" >> /etc/iptables/rules.v6
   echo ":OUTPUT DROP [0:0]" >> /etc/iptables/rules.v6
   echo "COMMIT" >> /etc/iptables/rules.v6
else
   echo "Le kernel n'est pas compatible"
fi

cat >/etc/iptables/rules.v4 <<EOF
# Generated by iptables-save v1.6.0 on Thu Aug 16 17:41:17 2018
*filter
:INPUT ACCEPT [1543:124410]
:FORWARD ACCEPT [0:0]
:OUTPUT ACCEPT [1451:141500]

# Allow internal traffic on the loopback device
-A INPUT -i lo -j ACCEPT

# Continue connections that are already established or related to an established connection
-A INPUT -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT

# Drop non-conforming packets, such as malformed headers, etc.
-A INPUT -m conntrack --ctstate INVALID -j DROP

# SSH
-A INPUT -p tcp -m tcp --dport 22 -j ACCEPT

# DHCP used by OVH
-A INPUT -p udp --dport 67:68 --sport 67:68 -j ACCEPT

# HTTP + HTTPS
-A INPUT -p tcp -m multiport --dports 80,443 -j ACCEPT

# Email (postfix + devecot)
# 25 = smtp, 587 = submission and 993 = IMAPS
-A INPUT -p tcp -m multiport --dports 25,587,993 -j ACCEPT

# NTP
-A INPUT -p udp --dport 123 -j ACCEPT

# Chain for preventing ping flooding - up to 6 pings per second from a single
# source, again with log limiting. Also prevents us from ICMP REPLY flooding
# some victim when replying to ICMP ECHO from a spoofed source.
-N ICMPFLOOD
-A ICMPFLOOD -m recent --name ICMP --set --rsource
-A ICMPFLOOD -m recent --name ICMP --update --seconds 1 --hitcount 6 --rsource --rttl -m limit --limit 1/sec --limit-burst 1 -j LOG --log-prefix "iptables[ICMP-flood]: "
-A ICMPFLOOD -m recent --name ICMP --update --seconds 1 --hitcount 6 --rsource --rttl -j DROP
-A ICMPFLOOD -j ACCEPT

# Permit useful IMCP packet types.
# Note: RFC 792 states that all hosts MUST respond to ICMP ECHO requests.
# Blocking these can make diagnosing of even simple faults much more tricky.
# Real security lies in locking down and hardening all services, not by hiding.
-A INPUT -p icmp --icmp-type 0  -m conntrack --ctstate NEW -j ACCEPT
-A INPUT -p icmp --icmp-type 3  -m conntrack --ctstate NEW -j ACCEPT
-A INPUT -p icmp --icmp-type 8  -m conntrack --ctstate NEW -j ICMPFLOOD
-A INPUT -p icmp --icmp-type 11 -m conntrack --ctstate NEW -j ACCEPT

# Drop all incoming malformed NULL packets
-A INPUT -p tcp --tcp-flags ALL NONE -j DROP

# Drop syn-flood attack packets
-A INPUT -p tcp ! --syn -m conntrack --ctstate NEW -j DROP

# Drop incoming malformed XMAS packets
-A INPUT -p tcp --tcp-flags ALL ALL -j DROP

# Nagios NRPE
-A INPUT -p tcp -m tcp --dport 5666 -j ACCEPT

COMMIT
# Completed on Thu Aug 16 17:41:17 2018
EOF

# Redemarrage du pare-feu
service netfilter-persistent restart

# Mis a jour de ClamAV
service clamav-freshclam stop
freshclam
service clamav-freshclam start
service clamav-freshclam restart

# Installation de Nagios
apt-get -y install nagios-nrpe-server
apt-get -y install nagios-plugins

# Configuration de Nagios
#sed -i -e "s/allowed_hosts=127.0.0.1/allowed_hosts=ipduserveurdemonitoring/g" /etc/nagios/nrpe.cfg

# Automatiser le demarrage de Nagios
update-rc.d nagios-nrpe-server defaults
service nagios-nrpe-server restart

# Definition des commandes
echo '' >> /etc/nagios/nrpe.cfg
echo 'command[check_disk_/]=/usr/lib/nagios/plugins/check_disk -w 20 -c 10 -p /' >> /etc/nagios/nrpe.cfg
echo 'command[check_load]=/usr/lib/nagios/plugins/check_load -w 50 -c 80' >> /etc/nagios/nrpe.cfg
echo 'command[check_http]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 80 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_https]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 443 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_panel]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 8000 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_mysql]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 3306 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_ssh]=/usr/lib/nagios/plugins/check_ssh -p 22 127.0.0.1' >> /etc/nagios/nrpe.cfg
echo 'command[check_dns]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 53 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_dovecot]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 143 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_postfix]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 25 -w 5 -c 8' >> /etc/nagios/nrpe.cfg

# Redemarrage du service Nagios
service nagios-nrpe-server restart

# Mise en place du fichier sudoers
mv /etc/sudoers /etc/sudoers.bak
mv sudoers /etc/sudoers
chown root:root /etc/sudoers

# Mise en place du core
mv core /core
chmod -R +x /core

# Mise en place du panel
mv app /app
chown -R  debian:www-data /app
mkdir /applogs

# Creation de la base de donnees du panel
mysql -e 'CREATE DATABASE `'emailhostingfe'`;'
mysql -e 'GRANT ALL PRIVILEGES ON `'emailhostingfe'`.* TO `'emailhostingfe'`@`localhost` IDENTIFIED BY "'$emailhostingfepassword'";'
mysql -e "flush privileges"

# Population de la base de donnees
mysql -u emailhostingfe -p$emailhostingfepassword emailhostingfe < emailhostingfe.sql
rm emailhostingfe.sql

# Mise en place du mot de passe de la base de donnees
sed -i "s/emailhostingfepass/$emailhostingfepassword/g" /app/config/db.connect.php

# Suppression des vhosts
rm /etc/apache2/sites-enabled/*
rm /etc/apache2/sites-available/*

# Creation du Vhost RoundCube
echo "Listen 80" > /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "<VirtualHost *>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "        ServerAdmin webmaster@localhost" >> /etc/apache2/sites-available/000-default.conf
echo "        DocumentRoot /var/lib/roundcube" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "        ErrorLog /var/log/apache2/error.log" >> /etc/apache2/sites-available/000-default.conf
echo "        CustomLog /var/log/apache2/access.log combined" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo '<Directory "/var/lib/roundcube/">' >> /etc/apache2/sites-available/000-default.conf
echo "        Options FollowSymLinks" >> /etc/apache2/sites-available/000-default.conf
echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
echo "        Order allow,deny" >> /etc/apache2/sites-available/000-default.conf
echo "        Allow from all" >> /etc/apache2/sites-available/000-default.conf
echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "Listen 8000" >> /etc/apache2/sites-available/000-default.conf
echo "<VirtualHost *:8000>" >> /etc/apache2/sites-available/000-default.conf
echo "	" >> /etc/apache2/sites-available/000-default.conf
echo "    Alias /phpmyadmin /usr/share/phpmyadmin" >> /etc/apache2/sites-available/000-default.conf
echo "    DocumentRoot /app" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "    LogLevel warn" >> /etc/apache2/sites-available/000-default.conf
echo "    ErrorLog /applogs/error.log" >> /etc/apache2/sites-available/000-default.conf
echo "    CustomLog /applogs/access.log combined" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "    <Directory /app>" >> /etc/apache2/sites-available/000-default.conf
echo "        Options -Indexes +FollowSymLinks +MultiViews" >> /etc/apache2/sites-available/000-default.conf
echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf
echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Activation de RoundCube
a2ensite 000-default

# Redemarrage du service WEB
service apache2 restart

# Suppression des fichiers
rm setup.sh
rm LICENSE
rm README.md
