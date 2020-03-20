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
	require_once('../../config/class.php');
	require_once('../../config/db.connect.php');
	
	// Initialisation de l'objet
	$database = new db();
	
	// Protection de la connectivité
	if (!empty($_SESSION['webhostingfe'])){
		
		// Protection des informations
		if ( !empty($_POST['email']) ){
			
			// Récupération des informations
			if (!empty($_POST['firstname'])){ $firstname = htmlspecialchars($_POST['firstname']); }else{ $firstname = ''; }
			if (!empty($_POST['lastname'])){ $lastname = htmlspecialchars($_POST['lastname']); }else{ $lastname = ''; }
			$email = htmlspecialchars($_POST['email']);
			
			// Modification du compte avec le mot de passe
			if (!empty($_POST['password'])){
				
				// Préparation de la mise à jour du compte
				$sql = 'UPDATE `users` SET `lastname`=:lastname,`firstname`=:firstname,`email`=:email,`password`=:password WHERE id=:id';
				$database->query($sql);
				
				// Récupération de l'id
				$database->bind(':id', $_SESSION['webhostingfe']);
				
				// Mise à jour du nom
				$database->bind(':lastname', $lastname);
				
				// Mise à jour du prenom
				$database->bind(':firstname', $firstname);
				
				// Mise à jour de l'adresse mail
				$database->bind(':email', $email);
				
				// Hashage du mot de passe
				$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
				
				// Mise à jour du mot de passe
				$database->bind(':password', $password);
				
				// Mise à jour du compte
				$database->execute();
				
				// Définition de l'alerte
				$_SESSION['tmp']['myaccount']['type'] = 'success';
				$_SESSION['tmp']['myaccount']['msgbox'] = 'Mise à jour du compte réussi!';
				
				// Redirection
				header('Location:../../?service=myaccount');
				
			// Modification du compte sans le mot de passe
			}else{
				
				// Préparation de la mise à jour du compte
				$sql = 'UPDATE `users` SET `lastname`=:lastname,`firstname`=:firstname,`email`=:email WHERE id=:id';
				$database->query($sql);
				
				// Récupération de l'id
				$database->bind(':id', $_SESSION['webhostingfe']);
				
				// Mise à jour du nom
				$database->bind(':lastname', $lastname);
				
				// Mise à jour du prenom
				$database->bind(':firstname', $firstname);
				
				// Mise à jour de l'adresse mail
				$database->bind(':email', $email);
				
				// Mise à jour du compte
				$database->execute();
				
				// Définition de l'alerte
				$_SESSION['tmp']['myaccount']['type'] = 'success';
				$_SESSION['tmp']['myaccount']['msgbox'] = 'Mise à jour du compte réussi!';
				
				// Redirection
				header('Location:../../?service=myaccount');
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['myaccount']['type'] = 'warning';
			$_SESSION['tmp']['myaccount']['msgbox'] = 'Le champ "Adresse Mail" est obligatoire !';
			
			// Redirection
			header('Location:../../?service=myaccount');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['login']['type'] = 'danger';
		$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
		
		// Redirection
		header('Location:../logout.php');
		
	}
	
?>