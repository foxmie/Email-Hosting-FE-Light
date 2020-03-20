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
?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-header">
												<h4 class="card-title"><center>Mon Compte</center></h4><hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['myaccount']['type']) and !empty($_SESSION['tmp']['myaccount']['msgbox']) ){
													
												?>			
												<div class="alert alert-<?= $_SESSION['tmp']['myaccount']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['myaccount']['msgbox'] ?> </center>
													</span>
												</div>				
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['myaccount']['type']);
														unset($_SESSION['tmp']['myaccount']['msgbox']);
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<form class="form" method="POST" action="scripts/pages/myaccount.php">
									<div class="card ">
										<div class="card-body ">
											<div class="row">
												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Nom</label>
														<input class="form-control" type="text" name="lastname" value="<?php if (!empty($rowUsers['lastname'])){ echo $rowUsers['lastname']; } ?>" />
													</div>
													<div class="form-group">
														<label>Prénom</label>
														<input class="form-control" type="text" name="firstname" value="<?php if (!empty($rowUsers['firstname'])){ echo $rowUsers['firstname']; } ?>" />
													</div>
												</div>
												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Adresse Mail <font color="red">*</font></label>
														<input class="form-control" type="email" name="email" required value="<?php if (!empty($rowUsers['email'])){ echo $rowUsers['email']; } ?>" />
													</div>
													<div class="form-group">
														<label>Mot de passe</label>
														<input class="form-control" type="password" name="password" />
													</div>
												</div>
											</div>
											<button type="submit" class="btn btn-info btn-fill pull-right">Mettre à jour</button>
											<div class="clearfix"></div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>