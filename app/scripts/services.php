<?php
	
	/*
	
	This file is part of Email Hosting FE Light.

    Email Hosting FE Light is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Email Hosting FE Light is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Email Hosting FE Light.  If not, see <https://www.gnu.org/licenses/>.

	*/
	
	// Démarrage des sessions
	session_start();
	
	// EMP CORE
	$core = '/core';
	
	// Récupération de la configuration
	require_once('../config/class.php');
	require_once('../config/db.connect.php');
	
	// Initialisation de l'objet
	$database = new db();
	
	// Protection
	if (!empty($_GET['service'])){
		
		// Protection de la connectivité
		if (!empty($_SESSION['webhostingfe'])){
			
			// Service WEB
			if ( htmlspecialchars($_GET['service']) == '1' ){
						
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "WEB" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
							
			// Service MySQL
			}else if ( htmlspecialchars($_GET['service']) == '2' ){
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "MySQL" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service Dovecot
			}else if ( htmlspecialchars($_GET['service']) == '3' ){
				
						
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "Dovecot" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service Postfix
			}else if ( htmlspecialchars($_GET['service']) == '4' ){
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "Postfix" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service Fail2Ban
			}else if ( htmlspecialchars($_GET['service']) == '5' ){
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "Fail2Ban" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service SpamAssassin
			}else if ( htmlspecialchars($_GET['service']) == '6' ){
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "SpamAssassin" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service ClamAV
			}else if ( htmlspecialchars($_GET['service']) == '7' ){
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "ClamAV" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service Pare-feu
			}else if ( htmlspecialchars($_GET['service']) == '8' ){
						
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "Pare-feu" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Erreur interne
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'danger';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Erreur irécupérable !';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['login']['type'] = 'danger';
			$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
			
			// Redirection
			header('Location:logout.php');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['dashboard']['type'] = 'danger';
		$_SESSION['tmp']['dashboard']['msgbox'] = 'Erreur irécupérable !';
		
		// Redirection
		header('Location:../?service=dashboard');
		
	}
?>