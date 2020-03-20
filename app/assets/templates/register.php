<!--

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

-->
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
		<div class="wrapper wrapper-full-page">
			<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute">
				<div class="container">
					<div class="navbar-wrapper">
						<a class="navbar-brand" href="">Email Hosting FE Light</a>
						<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-bar burger-lines"></span>
							<span class="navbar-toggler-bar burger-lines"></span>
							<span class="navbar-toggler-bar burger-lines"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse justify-content-end" id="navbar"></div>
				</div>
			</nav>
			<div class="full-page register-page section-image" data-color="purple" data-image="assets/img/bg5.jpg">
				<div class="content">
					<div class="container">
						<div class="card card-register card-plain text-center">
							<div class="card-header ">
								<div class="row  justify-content-center">
									<div class="col-md-8">
										<div class="header-text">
											<h2 class="card-title">Email Hosting FE Light</h2>
											<h4 class="card-subtitle">Installation du panel</h4>
											<hr />
										</div>
									</div>
								</div>
							</div>
							<div class="card-body ">
								<?php
									
									if ( !empty($_SESSION['tmp']['register']['type']) and !empty($_SESSION['tmp']['register']['msgbox']) ){
									
								?>
								<div class="alert alert-<?= $_SESSION['tmp']['register']['type'] ?>">
									<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
										<i class="nc-icon nc-simple-remove"></i>
									</button>
									<span>
										<center> <?= $_SESSION['tmp']['register']['msgbox'] ?> </center>
									</span>
								</div>
								<?php
										// Suppression des sessions temporaires
										unset($_SESSION['tmp']['register']['type']);
										unset($_SESSION['tmp']['register']['msgbox']);
									}
								?>
								<div class="row">
									<div class="col-md-4"></div>
									<div class="col-md-4">
										<form method="POST" action="scripts/register.php">
											<div class="card card-plain">
												<div class="content">
													<div class="form-group">
														<label><font color="white">Adresse mail</font> <font color="red"> * </font></label>
														<input name="mail" type="email" placeholder="Veuillez saisir une adresse mail" class="form-control" required />
													</div>
													<div class="form-group">
														<label><font color="white">Mot de passe</font> <font color="red"> * </font></label>
														<input name="password" type="password" placeholder="Veuiller saisir un mot de passe" class="form-control"required />
													</div>
													<div class="form-group">
														<label><font color="white">Confirmation du mot de passe</font> <font color="red"> * </font></label>
														<input name="repeatpassword" type="password" placeholder="Veuillez saisir à nouveau votre mot de passe" class="form-control"required />
													</div>
												</div>
												<div class="footer text-center">
													<button type="submit" class="btn btn-fill btn-neutral btn-wd">Créer le compte</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
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
	<script src="assets/js/demo.js"></script>
	<script>
		$(document).ready(function() {
			demo.checkFullPageBackgroundImage();

			setTimeout(function() {
				// after 1000 ms we add the class animated to the login/register card
				$('.card').removeClass('card-hidden');
			}, 700)
		});
	</script>
</HTML>