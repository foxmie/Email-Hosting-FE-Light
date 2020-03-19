# Email-Hosting-FE-Light
Panel de gestion Email (Light)
### Prérequis :
 - Debian 9
 - Accès root
 
###  Clonage du repositories
``apt-get update && apt-get -y install git`` 
``git clone https://github.com/alexis77370/Email-Hosting-FE-Light.git``
 
###  Installation du panel
 ``cd Email-Hosting-FE-Light``
 
 ``chmod +x setup.sh``
 
 ``./setup.sh``
 
###  Accès au panel de gestion :
 ``http://ipv4:8000``
 
###  Le script d'installation installera et configurera automatiquement les services suivants :
 - Apache2
 - CertBot
 - MariaDB
 - PostFix
 - DovCot (Core, IMAP, LMTPD, Managesieved, MySQL, Sieve)
 - RoundCube
 - Nagios NRPE Client
 - OpenSSH
 - ClamAV
 - ipTables
 - Fail2Ban
 - SpamAssassin
 
## Licences
- Ce projet est sous licence ``GNU GENERAL PUBLIC LICENSE V3``
- Bootstrap du panel : [Light Bootstrap Dashboard Pro](https://demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/dashboard.html) - ``MIT Developer License ``
