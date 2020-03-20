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
	
	// Protection du domaine
	if ( !empty($_POST['domain']) ){
	
		// Protection de la connectivité
		if (!empty($_SESSION['webhostingfe'])){
			
			// Protection des actions
			if ( !empty($_POST['action']) ){
				
				// Creation d'une boite mail
				if ( $_POST['action'] == 'create' ){
					
					// Récupération du domaine
					$domain = htmlspecialchars($_POST['domain']);
					$domain = str_replace("@", "", $domain);
					
					// Protection des variables
					if ( !empty($_POST['user']) AND !empty($_POST['domain']) AND !empty($_POST['password']) AND !empty($_POST['repeatpassword']) ){
						
						// Récupération des variables
						$user = htmlspecialchars($_POST['user']);
						$password = htmlspecialchars($_POST['password']);
						$repeatpassword = htmlspecialchars($_POST['repeatpassword']);
						if ( !empty($_POST['description']) ){ $description = htmlspecialchars($_POST['description']); }else{ $description = ''; }
						
						// Vérification des mots de passes
						if ( $password == $repeatpassword ){
							
							// Verification de la disponibilite du compte
							$database->query("SELECT * FROM mailaddress WHERE domain = :domain AND user = :user");
							$database->bind(':domain', $domain);
							$database->bind(':user', $user);
							$row = $database->resultset();
							$countfqdn = $database->rowCount();

							if ($countfqdn > 0){
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'warning';
								$_SESSION['tmp']['domaine']['msgbox'] = 'L\'adresse mail "'.$user.'@'.$domain.'" existe déjà ! ';
								
								// Redirection
								header('Location:../../?service=domaine&domain='.$domain);
								
							}else{
								
								// Verification de la disponibilite du compte
								$database->query("SELECT * FROM aliases WHERE domain = :domain AND user = :user");
								$database->bind(':domain', $domain);
								$database->bind(':user', $user);
								$row = $database->resultset();
								$countfqdn = $database->rowCount();

								if ($countfqdn > 0){
									
									// Définition de l'alerte
									$_SESSION['tmp']['domaine']['type'] = 'warning';
									$_SESSION['tmp']['domaine']['msgbox'] = 'L\'alias "'.$user.'@'.$domain.'" existe déjà ! ';
									
									// Redirection
									header('Location:../../?service=domaine&domain='.$domain);
									
								}else{
									
									// Insertion de l'adresse mail dans la base de donnees
									$database->query('INSERT INTO `mailaddress`(`id`, `user`, `description`, `domain`, `state`) VALUES (:id, :user, :description, :domain, :state)');
									$database->bind(':id', '');
									$database->bind(':user', $user);
									$database->bind(':description', $description);
									$database->bind(':domain', $domain);
									$database->bind(':state', '1');
									$database->execute();
									
									// Définition de l'alerte
									$_SESSION['tmp']['domaine']['type'] = 'success';
									$_SESSION['tmp']['domaine']['msgbox'] = 'L\'adresse mail "'.$user.'@'.$domain.'" à correctement été créer';
									
									// Redirection
									header('Location:../../?service=domaine&domain='.$domain);
									
								}
						
							}
							
							
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['domaine']['type'] = 'warning';
							$_SESSION['tmp']['domaine']['msgbox'] = 'Les mots de passe ne concordent pas ! ';
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$domain);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['domaine']['type'] = 'warning';
						$_SESSION['tmp']['domaine']['msgbox'] = 'Les champs "Compte utilisateur", "Domaine", "Mot de passe" et "Confirmation du mot de passe" sont obligatoire ! ';
						
						// Redirection
						header('Location:../../?service=domaine&domain='.$domain);
						
					}
					
				// Création d'un alias
				}else if ( $_POST['action'] == 'createalias' ){
					
					// Récupération du domaine
					$domain = htmlspecialchars($_POST['domain']);
					$domain = str_replace("@", "", $domain);
					
					// Protection des variables
					if ( !empty($_POST['user']) AND !empty($_POST['domain']) AND !empty($_POST['destination']) ){
						
						// Récupération des variables
						$user = htmlspecialchars($_POST['user']);
						$destination = htmlspecialchars($_POST['destination']);
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM aliases WHERE domain = :domain AND user = :user");
						$database->bind(':domain', $domain);
						$database->bind(':user', $user);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							// Définition de l'alerte
							$_SESSION['tmp']['domaine']['type'] = 'warning';
							$_SESSION['tmp']['domaine']['msgbox'] = 'L\'alias "'.$user.'@'.$domain.'" existe déjà ! ';
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$domain);
							
						}else{
							
							// Verification de la disponibilite du compte
							$database->query("SELECT * FROM mailaddress WHERE domain = :domain AND user = :user");
							$database->bind(':domain', $domain);
							$database->bind(':user', $user);
							$row = $database->resultset();
							$countfqdn = $database->rowCount();

							if ($countfqdn > 0){
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'warning';
								$_SESSION['tmp']['domaine']['msgbox'] = 'L\'adresse mail "'.$user.'@'.$domain.'" existe déjà ! ';
								
								// Redirection
								header('Location:../../?service=domaine&domain='.$domain);
								
							}else{
								
								// Insertion de l'alias dans la base de donnees
								$database->query('INSERT INTO `aliases`(`id`, `user`, `domain`, `destination`) VALUES (:id, :user, :domain, :destination)');
								$database->bind(':id', '');
								$database->bind(':user', $user);
								$database->bind(':domain', $domain);
								$database->bind(':destination', $destination);
								$database->execute();
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'success';
								$_SESSION['tmp']['domaine']['msgbox'] = 'L\'alias "'.$user.'@'.$domain.'" à correctement été créer';
								
								// Redirection
								header('Location:../../?service=domaine&domain='.$domain);
								
							}
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['domaine']['type'] = 'warning';
						$_SESSION['tmp']['domaine']['msgbox'] = 'Les champs "" sont obligatoire ! ';
						
						// Redirection
						header('Location:../../?service=domaine&domain='.$domain);
						
					}
				
				// Modification d'une boite mail
				}else if ( $_POST['action'] == 'set' ){
					
					// Protection des variables
					if ( !empty($_POST['id'] ) ){
						
						// Modification du mot de passe
						if ( !empty($_POST['password']) ){
							
							// Récupération des mots de passe
							$password = htmlspecialchars($_POST['password']);
							$repeatpassword = htmlspecialchars($_POST['repeatpassword']);
							
							// Vérification de la concordance des mots de passe
							if ( $password == $repeatpassword ){
								
								// Les mots de passe concordent
								
								
								// Recuperation de la description
								$id = htmlspecialchars($_POST['id']);
								$adressemail = htmlspecialchars($_POST['adressemail']);
								if ( !empty($_POST['description']) ){ $description = htmlspecialchars($_POST['description']); }else{ $description = ''; }
								
								// Mise à jour de la description
								$database->query("UPDATE `mailaddress` SET `description` = :description WHERE `mailaddress`.`id` = :id ");
								$database->bind(':id', $id);
								$database->bind(':description', $description);
								$database->execute();
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'success';
								$_SESSION['tmp']['domaine']['msgbox'] = 'Le compte mail "'.$adressemail.'" à correctement été mis à jour';
								
								// Redirection
								header('Location:../../?service=domaine&domain='.$_POST['domain']);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'warning';
								$_SESSION['tmp']['domaine']['msgbox'] = 'Les mots de passe ne concordent pas ! ';
								
								// Redirection
								header('Location:../../?service=domaine&domain='.$_POST['domain']);
								
							}
							
						}else{
						
							// Recuperation de la description
							$id = htmlspecialchars($_POST['id']);
							$adressemail = htmlspecialchars($_POST['adressemail']);
							if ( !empty($_POST['description']) ){ $description = htmlspecialchars($_POST['description']); }else{ $description = ''; }
							
							// Mise à jour de la description
							$database->query("UPDATE `mailaddress` SET `description` = :description WHERE `mailaddress`.`id` = :id ");
							$database->bind(':id', $id);
							$database->bind(':description', $description);
							$database->execute();
							
							// Définition de l'alerte
							$_SESSION['tmp']['domaine']['type'] = 'success';
							$_SESSION['tmp']['domaine']['msgbox'] = 'Le compte mail "'.$adressemail.'" à correctement été mis à jour';
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$_POST['domain']);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['domaine']['type'] = 'danger';
						$_SESSION['tmp']['domaine']['msgbox'] = 'Erreur irécupérable !';
						
						// Redirection
						header('Location:../../?service=domaine&domain='.$_POST['domain']);
						
					}
					
				// Etat d'une boite mail
				}else if ( $_POST['action'] == 'state' ){
					
					// Protection
					if ( !empty($_POST['id']) and !empty($_POST['mailaddress']) ){
						
						// Récupération des valeurs
						$id = htmlspecialchars($_POST['id']);
						$state = htmlspecialchars($_POST['state']);
						$mailaddress = htmlspecialchars($_POST['mailaddress']);
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM mailaddress WHERE id = :id");
						$database->bind(':id', $id);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							// Définition de l'action
							if ( $state == '0' ){
								
								// Modification de l'etat de la boite mail
								$database->query("UPDATE `mailaddress` SET `state` = '1' WHERE `mailaddress`.`id` = :id");
								$database->bind(':id', $id);
								$database->execute();
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'success';
								$_SESSION['tmp']['domaine']['msgbox'] = 'Activation en cours de la boite mail : '.$mailaddress;
							
							}else if ( $state == '1' ){
								
								// Modification de l'etat de la boite mail
								$database->query("UPDATE `mailaddress` SET `state` = '0' WHERE `mailaddress`.`id` = :id");
								$database->bind(':id', $id);
								$database->execute();
								
								// Définition de l'alerte
								$_SESSION['tmp']['domaine']['type'] = 'success';
								$_SESSION['tmp']['domaine']['msgbox'] = 'Désactivation en cours de la boite mail : '.$mailaddress;
							
							}
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$_POST['domain']);
							
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['domaine']['type'] = 'warning';
							$_SESSION['tmp']['domaine']['msgbox'] = 'Le domaine "'.$domain.'" n\'existe pas !';
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$_POST['domain']);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['domaine']['type'] = 'danger';
						$_SESSION['tmp']['domaine']['msgbox'] = 'Erreur irécupérable !';
						
						// Redirection
						header('Location:../../?service=domaine&domain='.$_POST['domain']);
						
					}
					
				// Suppression d'une boite mail
				}else if ( $_POST['action'] == 'delete' ){
					
					// Protection des variables
					if ( $_POST['id'] ){
						
						// Recuperation des variables
						$id = htmlspecialchars($_POST['id']);
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM mailaddress WHERE id = :id");
						$database->bind(':id', $id);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							// Suppression de l'adresse mail
							$database->query('DELETE FROM `mailaddress` WHERE id = :id');
							$database->bind(':id', $id);
							$database->execute();
							
							// Définition de l'alerte
							$_SESSION['tmp']['domaine']['type'] = 'success';
							$_SESSION['tmp']['domaine']['msgbox'] = 'L\'adresse mail a correctement été supprimé !';
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$_POST['domain']);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['domaine']['type'] = 'danger';
						$_SESSION['tmp']['domaine']['msgbox'] = 'Impossible de récuperer le compte a supprimer ';
						
						// Redirection
						header('Location:../../?service=domaine&domain='.$domain);
						
					}
					
				// Suppression d'un alias
				}else if ( $_POST['action'] == 'deletealias' ){
					
					// Protection des variables
					if ( $_POST['id'] ){
						
						// Recuperation des variables
						$id = htmlspecialchars($_POST['id']);
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM aliases WHERE id = :id");
						$database->bind(':id', $id);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							// Suppression de l'adresse mail
							$database->query('DELETE FROM `aliases` WHERE id = :id');
							$database->bind(':id', $id);
							$database->execute();
							
							// Définition de l'alerte
							$_SESSION['tmp']['domaine']['type'] = 'success';
							$_SESSION['tmp']['domaine']['msgbox'] = 'L\'alias a correctement été supprimé !';
							
							// Redirection
							header('Location:../../?service=domaine&domain='.$_POST['domain']);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['domaine']['type'] = 'danger';
						$_SESSION['tmp']['domaine']['msgbox'] = 'Impossible de récuperer le compte a supprimer ';
						
						// Redirection
						header('Location:../../?service=domaine&domain='.$domain);
						
					}
					
				// Erreur Interne
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['domaine']['type'] = 'danger';
					$_SESSION['tmp']['domaine']['msgbox'] = 'Erreur Interne !';
					
					// Redirection
					header('Location:../../?service=domaine&domain='.$_POST['domain']);
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['domaine']['type'] = 'danger';
				$_SESSION['tmp']['domaine']['msgbox'] = 'Erreur Interne !';
				
				// Redirection
				header('Location:../../?service=domaine&domain='.$_POST['domain']);
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['login']['type'] = 'danger';
			$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
			
			// Redirection
			header('Location:../logout.php');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['email']['type'] = 'danger';
		$_SESSION['tmp']['email']['msgbox'] = 'Impossible de récupérer le domaine !';
		
		// Redirection
		header('Location:../../?service=email');
		
	}
	
?>