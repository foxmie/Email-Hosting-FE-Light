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
	if ( !empty($_POST['mail']) and !empty($_POST['password']) and !empty($_POST['repeatpassword']) ){
		
		// Récupération des informations
		$mail = htmlspecialchars($_POST['mail']);
		$password = htmlspecialchars($_POST['password']);
		$repeatpassword = htmlspecialchars($_POST['repeatpassword']);
		
		// Vérification des mots de passe
		if ($password != $repeatpassword){
			
			// Définition de l'alerte
			$_SESSION['tmp']['register']['type'] = 'warning';
			$_SESSION['tmp']['register']['msgbox'] = 'Les mots de passe ne correspondent pas !';
			
			// Les mots de passe ne correspondent pas
			header('Location:../?service=register');
			
		}else{
			
			// Hashage du mot de passe
			$password = password_hash($password, PASSWORD_DEFAULT);
			
			// Création du compte
			$database->query('INSERT INTO `users`(`id`, `firstname`, `lastname`, `email`, `password`) VALUES (:id, :firstname, :lastname, :email, :password)');
			$database->bind(':id', '');
			$database->bind(':firstname', '');
			$database->bind(':lastname', '');
			$database->bind(':email', $mail);
			$database->bind(':password', $password);
			$database->execute();
			
			// Définition de l'alerte
			$_SESSION['tmp']['login']['type'] = 'success';
			$_SESSION['tmp']['login']['msgbox'] = 'Le compte "'.$mail.'" à correctement été créer';
			$_SESSION['tmp']['login']['mailaddress'] = $mail;
			
			// Redirection
			header('Location:../?service=login');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['register']['type'] = 'warning';
		$_SESSION['tmp']['register']['msgbox'] = 'Les champs "Nom, Prénom, Adresse Mail, Mot de passe et Confirmation du mot de passe sont obligatoire !';
		
		// Les mots de passe ne correspondent pas
		header('Location:../?service=register');
		
	}
	
?>