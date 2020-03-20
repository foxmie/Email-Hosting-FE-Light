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
	
	// Verification de la prescence d'un domaine
	if ( !empty($_GET['domain'] ) ){

		// Récupération des informations sur le domaine
		$database->query("SELECT * FROM domaine WHERE domain = :domain");
		$database->bind(':domain', $_GET['domain']);
		$rowDomain = $database->single();
		
		$fodomaine = $rowDomain['domain'];
		
		if ( !empty($fodomaine)){
			
		
?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-header">
												<h4 class="card-title"><center><?= $rowDomain['domain'] ?></center></h4><hr>
												<div class="row">
													<div class="col-md-6">
														<center><p>WebMail : <a href="http://<?= $rowDomain['subdomain'].'.'.$rowDomain['domain'] ?>"><?= $rowDomain['subdomain'].'.'.$rowDomain['domain'] ?></a></p></center>
														</br><a href="?service=email"><button class="btn btn-primary"> <- Retournez à la gestion des domaines </button></a>
													</div>
													<div class="col-md-6">
														<p><font color="orange"><i class="fa fa-exclamation-triangle"></i></font> Veuillez ajouter au serveur DNS du domaine, ces champs pour garantir son bon fonctionnement : </p>
														<center>
															<ul>
																<li> IN MX 10 <?= $rowDomain['subdomain'] ?>.<?= $rowDomain['domain'] ?>.</li>
																<li><?= $rowDomain['subdomain'] ?> IN A <?= $_SERVER['SERVER_ADDR'] ?></li>
															</ul>
														</center>
													</div>
												</div>
												<hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['domaine']['type']) and !empty($_SESSION['tmp']['domaine']['msgbox']) ){
													
												?>			
												<div class="alert alert-<?= $_SESSION['tmp']['domaine']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['domaine']['msgbox'] ?> </center>
													</span>
												</div>				
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['domaine']['type']);
														unset($_SESSION['tmp']['domaine']['msgbox']);
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="card">
							<div class="card-body content-full-width">
								<ul role="tablist" class="nav nav-tabs">
									<li role="presentation" class="nav-item show active">
										<a class="nav-link" id="infomail-tab" href="#icon-infomail" data-toggle="tab"><i class="fa fa-info"></i> Liste des adresse mails </a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="createmail-tab" href="#icon-createmail" data-toggle="tab"><i class="fa fa-plus"></i> Ajout d'une adresse mail </a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="infoalias-tab" href="#icon-infoalias" data-toggle="tab"><i class="fa fa-info"></i> Liste des alias </a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="createalias-tab" href="#icon-createalias" data-toggle="tab"><i class="fa fa-plus"></i> Ajout d'un alias </a>
									</li>
								</ul>
								<div class="tab-content">
								
									<div id="icon-infomail" class="tab-pane fade show active" role="tabpanel" aria-labelledby="infomail-tab">
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card data-tables">
														<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
															<div class="toolbar"></div>
															<div class="fresh-datatables">
																<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
																	<thead>
																		<?php
																			
																			// Récupération des informations depuis la base de données
																			$database->query("SELECT * FROM mailaddress WHERE domain = '$fodomaine'");
																			$rowMailaddress = $database->resultset();
																			$maxMailaddress = $database->rowCount();
																			
																			// Vérification du nombre d'adresse mail
																			if ( $maxMailaddress == '0' ){
																				echo '<center></br><p><font color="orange"><i class="fa fa-exclamation-triangle"></i></font> Aucune adresse mail à affiché pour le domaine "'.$fodomaine.'" ! </p></center>';
																			}else{
																				
																		?>
																		<tr>
																			<td width="15%"><center>Compte</center></td>
																			<td width="35%"><center>Description</center></td>
																			<td width="40%"><center>Gestion</center></td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																				// Affichage des adresses mails
																				for ($i=0; $i<$maxMailaddress; $i++) {
																		?>
																		<tr>
																			<td><center> <?= $rowMailaddress[$i]['user'].'@'.$rowMailaddress[$i]['domain'] ?> </center></td>
																			<td><center> <?= $rowMailaddress[$i]['description'] ?> </center></td>
																			<td class="text-right">
																				<center>
																					<a class="btn btn-link btn-info" data-toggle="modal" data-target="#set<?= $rowMailaddress[$i]['id'] ?>"><i class="fa fa-edit"></i> Modifier le compte mail</a>
																					
																					<?php
																					if ( $rowMailaddress[$i]['state'] == '0' ){
																					?>
																					<a class="btn btn-link btn-success" data-toggle="modal" data-target="#state<?= $rowMailaddress[$i]['id'] ?>"><i class="fa fa-lock"></i> Activer la boite mail</a>
																					<?php
																						}else if ( $rowMailaddress[$i]['state'] == '1' ){
																					?>
																					<a class="btn btn-link btn-warning" data-toggle="modal" data-target="#state<?= $rowMailaddress[$i]['id'] ?>"><i class="fa fa-unlock"></i> Désactiver la boite mail</a>
																					<?php
																						}
																					?>
	
																					<a class="btn btn-link btn-danger" data-toggle="modal" data-target="#delete<?= $rowMailaddress[$i]['id'] ?>"><i class="fa fa-times"></i> Supprimer la boite mail</a>
																				</center>
																			</td>
																		</tr>
																		
																		<!-- Modifier le compte mail -->
																		<div class="modal" id="set<?= $rowMailaddress[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/domaine.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Modification de la boite mail</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<label>Adresse Mail</label>
																							<input type="text" class="form-control" name="adressemail" value="<?= $rowMailaddress[$i]['user'].'@'.$rowMailaddress[$i]['domain'] ?>" required readonly />
																							</br>
																							<label>Mot de passe</label>
																							<input type="password" class="form-control" name="password" placeholder="**********" />
																							<label>Confirmation du mot de passe</label>
																							<input type="password" class="form-control" name="repeatpassword" placeholder="**********" />
																							</br>
																							<label>Description</label>
																							<input type="text" class="form-control" name="description" value="<?= $rowMailaddress[$i]['description'] ?>" />
																							</br>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="set" readonly />
																							<input type="hidden" name="id" value="<?= $rowMailaddress[$i]['id'] ?>" readonly />
																							<input type="hidden" name="domain" value="<?= $rowDomain['domain'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Mettre à jour</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<!-- Etat du compte -->
																		<div class="modal" id="state<?= $rowMailaddress[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/domaine.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">
																								<?php
																									if ( $rowMailaddress[$i]['state'] == '0' ){
																										echo 'Activation de la boite mail';
																									}else if ( $rowMailaddress[$i]['state'] == '1' ){
																										echo 'Désactivation de la boite mail';
																									}
																								?>
																							</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<?php
																								if ( $rowMailaddress[$i]['state'] == '0' ){
																									echo 'Vous êtes sur le point d\'activer la boite mail : '.$rowMailaddress[$i]['user'].'@'.$rowMailaddress[$i]['domain'];
																								}else if ( $rowMailaddress[$i]['state'] == '1' ){
																									echo 'Vous êtes sur le point de désactiver la boite mail : '.$rowMailaddress[$i]['user'].'@'.$rowMailaddress[$i]['domain'];
																								}
																							?>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="state" readonly />
																							<input type="hidden" name="id" value="<?= $rowMailaddress[$i]['id'] ?>" readonly />
																							<input type="hidden" name="domain" value="<?= $rowDomain['domain'] ?>" readonly />
																							<input type="hidden" name="state" value="<?= $rowMailaddress[$i]['state'] ?>" readonly />
																							<input type="hidden" name="mailaddress" value="<?= $rowMailaddress[$i]['user'].'@'.$rowMailaddress[$i]['domain'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">
																								<?php
																									if ( $rowMailaddress[$i]['state'] == '0' ){
																										echo 'Activer la boite mail';
																									}else if ( $rowMailaddress[$i]['state'] == '1' ){
																										echo 'Désactiver la boite mail';
																									}
																								?>
																							</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<!-- Supprimer la boite mail -->
																		<div class="modal" id="delete<?= $rowMailaddress[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/domaine.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Suppression de l'adresse mail</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<p>Voulez vous vraiment supprimer l'adresse mail "<?= $rowMailaddress[$i]['user'].'@'.$rowMailaddress[$i]['domain'] ?>" ?</p>
																							<p> <font color="orange"><i class="fa fa-exclamation-triangle"></i></font> Cette action est irréversible ! </p>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="delete" readonly />
																							<input type="hidden" name="id" value="<?= $rowMailaddress[$i]['id'] ?>" readonly />
																							<input type="hidden" name="domain" value="<?= $rowDomain['domain'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Supprimer</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<?php
																				}
																			}
																		?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div id="icon-createmail" class="tab-pane fade" role="tabpanel" aria-labelledby="createmail-tab">
									
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="row">
															<div class="col-md-12">
																<div class="card-body">
																	<h4 class="card-title"><center>Création d'une adresse mail</center></h4><hr>
																	<form action="scripts/pages/domaine.php" method="POST">	
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Compte utilisateur</label>
																						<input class="form-control" type="text" name="user" required />
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Domaine</label>
																						<input class="form-control" type="text" name="domain" value="@<?= $rowDomain['domain'] ?>" readonly required />
																					</div>
																				</div>
																			</div>
																		</div>
																		
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Mot de passe</label>
																						<input class="form-control" type="password" name="password" required />
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Confirmation du mot de passe</label>
																						<input class="form-control" type="password" name="repeatpassword" required />
																					</div>
																				</div>
																			</div>
																		</div>
																				
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Description</label>
																						<input class="form-control" type="text" name="description" />
																					</div>
																				</div>
																			</div>
																		</div>
																		<input type="hidden" name="action" value="create" readonly />
																		<button type="submit" class="btn btn-primary btn-block">Créer la boite mail</button>
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									
									</div>
									
									<div id="icon-infoalias" class="tab-pane fade" role="tabpanel" aria-labelledby="infoalias-tab">
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card data-tables">
														<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
															<div class="toolbar"></div>
															<div class="fresh-datatables">
																<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
																	<thead>
																		<?php
																			
																			// Récupération des informations depuis la base de données
																			$database->query("SELECT * FROM aliases WHERE domain = '$fodomaine'");
																			$rowAliases = $database->resultset();
																			$maxAliases = $database->rowCount();
																			
																			// Vérification du nombre d'alias
																			if ( $maxAliases == '0' ){
																				echo '<center></br><p><font color="orange"><i class="fa fa-exclamation-triangle"></i></font> Aucun alias à affiché pour le domaine "'.$fodomaine.'" ! </p></center>';
																			}else{
																				
																		?>
																		<tr>
																			<td width="30%"><center>Alias</center></td>
																			<td width="30%"><center>Destination</center></td>
																			<td width="40%"><center>Gestion</center></td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																			
																				// Affichage des alias
																				for ($i=0; $i<$maxAliases; $i++) {
																			
																		?>
																		<tr>
																			<td><center> <?= $rowAliases[$i]['user'].'@'.$rowAliases[$i]['domain'] ?> </center></td>
																			<td><center> <?= $rowAliases[$i]['destination'] ?> </center></td>
																			<td class="text-right">
																				<center>
																					<a class="btn btn-link btn-danger" data-toggle="modal" data-target="#deletealias<?= $rowAliases[$i]['id'] ?>"><i class="fa fa-times"></i> Supprimer l'alias</a>
																				</center>
																			</td>
																		</tr>
																		
																		<!-- Supprimer la boite mail -->
																		<div class="modal" id="deletealias<?= $rowAliases[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/domaine.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Suppression de l'adresse mail</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<p>Voulez vous vraiment supprimer l'alias "<?= $rowAliases[$i]['user'].'@'.$rowAliases[$i]['domain'] ?>" ?</p>
																							<p> <font color="orange"><i class="fa fa-exclamation-triangle"></i></font> Cette action est irréversible ! </p>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="deletealias" readonly />
																							<input type="hidden" name="id" value="<?= $rowAliases[$i]['id'] ?>" readonly />
																							<input type="hidden" name="domain" value="<?= $rowDomain['domain'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Supprimer</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		<?php
																				}
																			}
																		?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div id="icon-createalias" class="tab-pane fade" role="tabpanel" aria-labelledby="createalias-tab">
									
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="row">
															<div class="col-md-12">
																<div class="card-body">
																	<h4 class="card-title"><center>Création d'un alias</center></h4><hr>
																	<form action="scripts/pages/domaine.php" method="POST">
																		
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Compte utilisateur</label>
																						<input class="form-control" type="text" name="user" required />
																					</div>
																				</div>
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Domaine</label>
																						<input class="form-control" type="text" name="domain" value="@<?= $rowDomain['domain'] ?>" readonly required />
																					</div>
																				</div>
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Email de Destination</label>
																						<input class="form-control" type="email" name="destination" required />
																					</div>
																				</div>
																			</div>
																		</div>
																		<input type="hidden" name="action" value="createalias" readonly required />
																		<button type="submit" class="btn btn-primary btn-block">Créer l'alias</button>
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									
									</div>
									
								</div>
							</div>
						</div>
					</div>
<?php
		}else{
?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-header">
												<h4 class="card-title"><center> Erreur Interne ! </center></h4><hr>
												<p><center>Le domaine n'existe pas ! <a href="?service=email">Retournez à la gestion des domaines</a></center></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<?php
		}
	}else{
?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-header">
												<h4 class="card-title"><center> Erreur Interne ! </center></h4><hr>
												<p><center> Aucun domaine n'est renseigné ! <a href="?service=email">Retournez à la gestion des domaines</a> </center></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<?php
	}
?>