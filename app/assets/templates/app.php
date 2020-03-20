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
	
	// Vérification de la connéctivité de l'utilisateur
	if (!empty($_SESSION['webhostingfe'])){
		
		// Récupération des informations sur l'utilisateur
		$database->query("SELECT lastname, firstname, email FROM users WHERE id = :id");
		$database->bind(':id', $_SESSION['webhostingfe']);
		$rowUsers = $database->single();
		
	}
?>
<!Doctype HTML>
<HTML lang="fr">
	<Head>
		<meta charset="utf-8" />
		<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<Title>Email Hosting FE Light</Title>
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/light-bootstrap-dashboard.css?v=2.0.1" rel="stylesheet" />
		<link href="assets/css/demo.css" rel="stylesheet" />
	</Head>
	<Body>
		<div class="wrapper">
			<div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">
				<div class="sidebar-wrapper">
					<div class="logo">
						<a href="http://www.creative-tim.com" class="simple-text logo-mini">
							EM
						</a>
						<a href="http://www.creative-tim.com" class="simple-text logo-normal">
							Hosting FE Light
						</a>
					</div>
					<ul class="nav">
						<li class="nav-item <?php if (!empty($_GET['service'])){ if ($_GET['service'] == 'dashboard'){ echo 'active'; } }?>">
							<a class="nav-link" href="?service=dashboard">
								<i class="nc-icon nc-chart-pie-35"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item <?php if (!empty($_GET['service'])){ if ($_GET['service'] == 'email'){ echo 'active'; } }?>">
							<a class="nav-link" href="?service=email">
								<i class="nc-icon nc-email-85"></i>
								<p>Gestion Email</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="main-panel">
				<nav class="navbar navbar-expand-lg ">
					<div class="container-fluid">
						<div class="navbar-wrapper">
							<div class="navbar-minimize">
								<button id="minimizeSidebar" class="btn btn-warning btn-fill btn-round btn-icon d-none d-lg-block">
									<i class="fa fa-ellipsis-v visible-on-sidebar-regular"></i>
									<i class="fa fa-navicon visible-on-sidebar-mini"></i>
								</button>
							</div>
							<a class="navbar-brand" href=""> Email Hosting FE Light </a>
						</div>
						<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-bar burger-lines"></span>
							<span class="navbar-toggler-bar burger-lines"></span>
							<span class="navbar-toggler-bar burger-lines"></span>
						</button>
						<div class="collapse navbar-collapse justify-content-end">
							<ul class="navbar-nav">
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<?php if (!empty($rowUsers['firstname'])){ echo $rowUsers['firstname']; } ?> <?php if (!empty($rowUsers['lastname'])){ echo $rowUsers['lastname']; } ?>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
										<a class="dropdown-item" href="?service=myaccount">
											<i class="nc-icon nc-badge"></i> Mon Compte
										</a>
										<div class="divider"></div>
										<a href="scripts/logout.php" class="dropdown-item text-danger">
											<i class="nc-icon nc-button-power"></i> Déconnection
										</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<div class="content">
					<?php
						
						if (!empty($_GET['service'])){
							require_once('pages/'.htmlspecialchars($_GET['service']).'.php');
						}
						
					?>
				</div>
			</div>
			<footer class="footer">
				<div class="container">
					<nav>
						<ul class="footer-menu"></ul>
						<p class="copyright text-center">
							© <?= date('Y') ?> <a href="http://foxmie.fr">FoxMie</a>
						</p>
					</nav>
				</div>
			</footer>
		</div>
	</Body>
	<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
	<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/plugins/bootstrap-switch.js"></script>
	<script src="assets/js/plugins/chartist.min.js"></script>
	<script src="assets/js/plugins/bootstrap-notify.js"></script>
	<script src="assets/js/plugins/jquery-jvectormap.js" type="text/javascript"></script>
	<script src="assets/js/plugins/moment.min.js"></script>
	<script src="assets/js/plugins/bootstrap-datetimepicker.js"></script>
	<script src="assets/js/plugins/sweetalert2.min.js" type="text/javascript"></script>
	<script src="assets/js/plugins/bootstrap-tagsinput.js" type="text/javascript"></script>
	<script src="assets/js/plugins/nouislider.js" type="text/javascript"></script>
	<script src="assets/js/plugins/bootstrap-selectpicker.js" type="text/javascript"></script>
	<script src="assets/js/plugins/jquery.validate.min.js" type="text/javascript"></script>
	<script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
	<script src="assets/js/plugins/bootstrap-table.js"></script>
	<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
	<script src="assets/js/plugins/fullcalendar.min.js"></script>
	<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.1" type="text/javascript"></script>
</HTML>