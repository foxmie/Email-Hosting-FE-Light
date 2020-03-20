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
		
		// Protection des actions
		if ( !empty($_POST['action']) ){
			
			// Ajout d'un domaine
			if ( $_POST['action'] == 'create' ){
				
				// Protection des valeurs
				if ( !empty($_POST['subdomain']) AND !empty($_POST['domain']) AND !empty($_POST['extension']) ){
					
					// Recuperation des valeurs
					$subdomain = htmlspecialchars($_POST['subdomain']);
					$domain = htmlspecialchars($_POST['domain']);
					$extension = htmlspecialchars($_POST['extension']);
					$domain = $domain.$extension;
					
					// Verification de la disponibilite du domaine
					$database->query("SELECT * FROM domaine WHERE domain = :domain");
					$database->bind(':domain', $domain);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Définition de l'alerte
						$_SESSION['tmp']['email']['type'] = 'warning';
						$_SESSION['tmp']['email']['msgbox'] = 'Le domaine "'.$domain.'" existe déjà ! ';
						
						// Redirection
						header('Location:../../?service=email');
						
					}else{
					
						// Insertion du domaine dans la base de donnees
						$database->query('INSERT INTO `domaine`(`id`, `subdomain`, `domain`, `letsencrypt`) VALUES (:id, :subdomain, :domain, :letsencrypt)');
						$database->bind(':id', '');
						$database->bind(':subdomain', $subdomain);
						$database->bind(':domain', $domain);
						$database->bind(':letsencrypt', '0');
						$database->execute();
						
						// Définition de l'alerte
						$_SESSION['tmp']['email']['type'] = 'success';
						$_SESSION['tmp']['email']['msgbox'] = 'Le domaine "'.$domain.'" à correctement été créer';
						
						// Redirection
						header('Location:../../?service=email');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['email']['type'] = 'warning';
					$_SESSION['tmp']['email']['msgbox'] = 'Les champs "Sous domaine", "Domaine" et "Extension" sont obligatoire !';
					
					// Redirection
					header('Location:../../?service=email');
					
				}
				
			// Suppression d'un domaine
			}else if ( $_POST['action'] == 'delete' ){
				
				// Protection des variables
				if ( !empty($_POST['id']) and !empty($_POST['domain']) ){
					
					// Recuperation des variables
					$id = htmlspecialchars($_POST['id']);
					$domain = htmlspecialchars($_POST['domain']);
					
					// Verification de la disponibilite du compte
					$database->query("SELECT * FROM domaine WHERE id = :id");
					$database->bind(':id', $id);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Suppression des adresse mail attaché au domaine
						$database->query('DELETE FROM `mailaddress` WHERE domain = :domain');
						$database->bind(':domain', $domain);
						$database->execute();
						
						// Suppression des alias attaché au domaine
						$database->query('DELETE FROM `aliases` WHERE domain = :domain');
						$database->bind(':domain', $domain);
						$database->execute();
						
						// Suppression du domaine
						$database->query('DELETE FROM `domaine` WHERE id = :id');
						$database->bind(':id', $id);
						$database->execute();
						
						// Définition de l'alerte
						$_SESSION['tmp']['email']['type'] = 'success';
						$_SESSION['tmp']['email']['msgbox'] = 'Le domaine "'.$domain.'" a correctement été supprimé !';
						
						// Redirection
						header('Location:../../?service=email');
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['email']['type'] = 'warning';
						$_SESSION['tmp']['email']['msgbox'] = 'Le domaine n\'existe pas !';
						
						// Redirection
						header('Location:../../?service=email');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['email']['type'] = 'warning';
					$_SESSION['tmp']['email']['msgbox'] = 'Erreur interne !';
					
					// Redirection
					header('Location:../../?service=email');
					
				}
				
			// Let's Encrypt
			}else if ( $_POST['action'] == "letsencrypt" ){
			
				// Protection de la connectivité
				if (!empty($_SESSION['webhostingfe'])){
					
					// Protection
					if ( !empty($_POST['id']) and !empty($_POST['domain']) ){
						
						// Récupération des valeurs
						$id = htmlspecialchars($_POST['id']);
						$domain = htmlspecialchars($_POST['domain']);
						$state = htmlspecialchars($_POST['state']);
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM domaine WHERE id = :id");
						$database->bind(':id', $id);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							// Définition de l'action
							if ( $state == '0' ){
								
								// Modification de let's encrypt dans la base de données
								$database->query("UPDATE `domaine` SET `letsencrypt` = '1' WHERE `domaine`.`id` = :id");
								$database->bind(':id', $id);
								$database->execute();
								
								// Définition de l'alerte
								$_SESSION['tmp']['email']['type'] = 'success';
								$_SESSION['tmp']['email']['msgbox'] = 'Activation en cours du certificat SSL let\'s encrypt sur le compte : '.$domain;
							
							}else if ( $state == '1' ){
								
								// Modification de let's encrypt dans la base de données
								$database->query("UPDATE `domaine` SET `letsencrypt` = '0' WHERE `domaine`.`id` = :id");
								$database->bind(':id', $id);
								$database->execute();
								
								// Définition de l'alerte
								$_SESSION['tmp']['email']['type'] = 'success';
								$_SESSION['tmp']['email']['msgbox'] = 'Désactivation en cours du certificat SSL let\'s encrypt sur le compte : '.$domain;
							
							}
							
							// Redirection
							header('Location:../../?service=email');
							
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['email']['type'] = 'warning';
							$_SESSION['tmp']['email']['msgbox'] = 'Le domaine "'.$domain.'" n\'existe pas !';
							
							// Redirection
							header('Location:../../?service=email');
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['email']['type'] = 'danger';
						$_SESSION['tmp']['email']['msgbox'] = 'Erreur irécupérable !';
						
						// Redirection
						header('Location:../../?service=email');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['login']['type'] = 'danger';
					$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
					
					// Redirection
					header('Location:../logout.php');
					
				}
		
			// Erreur interne
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['email']['type'] = 'warning';
				$_SESSION['tmp']['email']['msgbox'] = 'Erreur interne !';
				
				// Redirection
				header('Location:../../?service=email');
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['email']['type'] = 'warning';
			$_SESSION['tmp']['email']['msgbox'] = 'Erreur interne !';
			
			// Redirection
			header('Location:../../?service=email');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['login']['type'] = 'danger';
		$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
		
		// Redirection
		header('Location:../logout.php');
		
	}
	
?>