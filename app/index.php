<?php
	
	/*
	
	This file is part of Web Hosting FE Light.

    Web Hosting FE Light is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Web Hosting FE Light is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Web Hosting FE Light.  If not, see <https://www.gnu.org/licenses/>.

	*/
	
	// Démarrage des sessions
	session_start();
	
	// Récupération de la configuration
	require_once('config/class.php');
	require_once('config/db.connect.php');
	
	// Initialisation de l'objet
	$database = new db();
	
	// Dossier des templates
	$emp_template = 'assets/templates/';
	
	// Vérification de l'installation
	$database->query("SELECT * FROM users");
	$row = $database->resultset();
	$countusers = $database->rowCount();
	
	// Le panel est installé
	if ($countusers > 0){
		
		// Récupération de la template
		if (!empty($_GET['service'])){
			
			// Vérification de la connectivité de l'utilisateur
			if (!empty($_SESSION['webhostingfe'])){
				
				// Template "app"
				$app = array("dashboard", "email", "domaine", "myaccount");
				if (in_array(htmlspecialchars($_GET['service']), $app)){
					
					// Récupération de la template "app"
					require_once($emp_template.'app.php');
					
				}else
				
				// Template "login"
				if (htmlspecialchars($_GET['service']) == 'login'){
					
					// Redirection vers le dashboard
					header('Location:?service=dashboard');
				
				}else
					
				// Template "register"
				if (htmlspecialchars($_GET['service']) == 'register'){
					
					// Redirection vers le dashboard
					header('Location:?service=dashboard');
					
				}else
					
				// Template non reconue
				{
					echo 'Template ERROR';
				}
				
			}else{
				
				// Template "app"
				$app = array("dashboard", "bind", "zone", "sftp", "mysql", "myaccount");
				if (in_array(htmlspecialchars($_GET['service']), $app)){
					
					// Redirection vers la page de connection
					header('Location:?service=login');
					
				}else
				
				// Template "login"
				if (htmlspecialchars($_GET['service']) == 'login'){
					
					// Récupération de la template "login"
					require_once($emp_template.'login.php');
				
				}else
					
				// Template "register"
				if (htmlspecialchars($_GET['service']) == 'register'){
					
					// Redirection vers la page de connection
					header('Location:?service=login');
					
				}else
					
				// Template non reconue
				{
					echo 'Template ERROR';
				}
				
			}
			
		}else{
			
			// Vérification de la connectivité de l'utilisateur
			if (!empty($_SESSION['webhostingfe'])){
				
				// Redirection vers le dashboard
				header('Location:?service=dashboard');
				
			}else{
				
				// Redirection vers la page de connection
				header('Location:?service=login');
				
			}
			
		}
		
	// Installation du panel
	}else{
		
		if (!empty($_GET['service'])){
			
			if ($_GET['service'] == 'register'){
				
				// Récupération de la template "login"
				require_once($emp_template.'register.php');
				
			}else{
				
				// Redirection vers la page d'installation
				header('Location:?service=register');
				
			}
			
		}else{
				
			// Redirection vers la page d'installation
			header('Location:?service=register');
			
		}

		
	}
	
	
?>