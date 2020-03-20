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
	
	// Récupération de la configuration
	require_once('../config/class.php');
	require_once('../config/db.connect.php');
	
	// Initialisation de l'objet
	$database = new db();
	
	// Protection
	if ( !empty($_POST['mail']) and !empty($_POST['password']) ){
		
		// Récupération des informations
		$mail = htmlspecialchars($_POST['mail']);
		$password = htmlspecialchars($_POST['password']);
		
		// Vérification de l'adresse mail
		$database->query("SELECT id, email, password FROM users WHERE email = :email");
		$database->bind(':email', $mail);
		$row = $database->single();
		$rowcount = $database->rowCount();
		
		// Vérification du mot de passe
		if ($rowcount > '0'){
			
			// Récupération du mot de passe dans la base de données
			$hash = $row['password'];
			
			// Vérification du mot de passe
			if (password_verify($password, $hash)){
				
				// Définition de l'id utilisateur
				$_SESSION['webhostingfe'] = $row['id'];
				
				// Redirection vers la page dashboard
				header('Location:../?service=dashboard');
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'warning';
				$_SESSION['tmp']['login']['msgbox'] = 'Adresse mail ou mot de passe incorrect !';
				$_SESSION['tmp']['login']['mailaddress'] = htmlspecialchars($_POST['mail']);
				
				// Les mots de passe ne correspondent pas
				header('Location:../?service=login');
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['login']['type'] = 'warning';
			$_SESSION['tmp']['login']['msgbox'] = 'Adresse mail ou mot de passe incorrect !';
			$_SESSION['tmp']['login']['mailaddress'] = htmlspecialchars($_POST['mail']);
			
			// Les mots de passe ne correspondent pas
			header('Location:../?service=login');
			
		}
		
	}
	
?>