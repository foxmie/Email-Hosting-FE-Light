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
												<h4 class="card-title"><center>Gestion Email</center></h4><hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['email']['type']) and !empty($_SESSION['tmp']['email']['msgbox']) ){
													
												?>			
												<div class="alert alert-<?= $_SESSION['tmp']['email']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['email']['msgbox'] ?> </center>
													</span>
												</div>				
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['email']['type']);
														unset($_SESSION['tmp']['email']['msgbox']);
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
										<a class="nav-link" id="info-tab" href="#icon-info" data-toggle="tab"><i class="fa fa-info"></i> Liste des domaines </a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="createzone-tab" href="#icon-createzone" data-toggle="tab"><i class="fa fa-plus"></i> Ajout d'un domaine </a>
									</li>
								</ul>
								<div class="tab-content">
								
									<div id="icon-info" class="tab-pane fade show active" role="tabpanel" aria-labelledby="info-tab">
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card data-tables">
														<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
															<div class="toolbar"></div>
															<div class="fresh-datatables">
																<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
																	<thead>
																		<tr>
																			<td width="30%"><center>Domaine</center></td>
																			<td width="30%"><center>WebMail</center></td>
																			<td width="40%"><center>Gestion</center></td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		
																			$database->query('SELECT * FROM domaine');
																			$rowDomain = $database->resultset();
																			
																			$maxDomain = $database->rowCount();
																			for ($i=0; $i<$maxDomain; $i++) {
																			
																		?>
																		<tr>
																			<td><center> <?= $rowDomain[$i]['domain'] ?> </center></td>
																			<td><center> <a target="<?= $rowDomain[$i]['subdomain'].'.'.$rowDomain[$i]['domain'] ?>" href="http://<?= $rowDomain[$i]['subdomain'].'.'.$rowDomain[$i]['domain'] ?>"><?= $rowDomain[$i]['subdomain'].'.'.$rowDomain[$i]['domain'] ?></a> </center></td>
																			<td class="text-right">
																				<center>
																					<a href="?service=domaine&domain=<?= $rowDomain[$i]['domain'] ?>" class="btn btn-link btn-info" ><i class="fa fa-envelope"></i> Gérer les boîtes mails</a>
																					
																					<?php
																					if ( $rowDomain[$i]['letsencrypt'] == '0' ){
																					?>
																					<a class="btn btn-link btn-success" data-toggle="modal" data-target="#letsencrypt<?= $rowDomain[$i]['id'] ?>"><i class="fa fa-lock"></i> Activer Let's Encrypt</a>
																					<?php
																						}else if ( $rowDomain[$i]['letsencrypt'] == '1' ){
																					?>
																					<a class="btn btn-link btn-warning" data-toggle="modal" data-target="#letsencrypt<?= $rowDomain[$i]['id'] ?>"><i class="fa fa-unlock"></i> Désactiver Let's Encrypt</a>
																					<?php
																						}
																					?>
																					
																					<a class="btn btn-link btn-danger" data-toggle="modal" data-target="#delete<?= $rowDomain[$i]['id'] ?>"><i class="fa fa-times"></i> Supprimer le domaine</a>
																				</center>
																			</td>
																		</tr>
																		
																		<!-- Let's Encrypt -->
																		<div class="modal" id="letsencrypt<?= $rowDomain[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/email.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">
																								<?php
																									if ( $rowDomain[$i]['letsencrypt'] == '0' ){
																										echo 'Activation du certificat SSL Let\'s Encrypt';
																									}else if ( $rowDomain[$i]['letsencrypt'] == '1' ){
																										echo 'Désactivation du certificat SSL Let\'s Encrypt';
																									}
																								?>
																							</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<?php
																								if ( $rowDomain[$i]['letsencrypt'] == '0' ){
																									echo 'Vous êtes sur le point d\'activer un certificat SSL Let\'s Encrypt pour le compte : '.$rowDomain[$i]['domain'];
																								}else if ( $rowDomain[$i]['letsencrypt'] == '1' ){
																									echo 'Vous êtes sur le point de supprimer le certificat SSL Let\'s Encrypt pour le compte : '.$rowDomain[$i]['domain'];
																								}
																							?>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="letsencrypt" readonly />
																							<input type="hidden" name="id" value="<?= $rowDomain[$i]['id'] ?>" readonly />
																							<input type="hidden" name="domain" value="<?= $rowDomain[$i]['domain'] ?>" readonly />
																							<input type="hidden" name="state" value="<?= $rowDomain[$i]['letsencrypt'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">
																								<?php
																									if ( $rowDomain[$i]['letsencrypt'] == '0' ){
																										echo 'Activer Let\'s Encrypt';
																									}else if ( $rowDomain[$i]['letsencrypt'] == '1' ){
																										echo 'Désactiver Let\'s Encrypt';
																									}
																								?>
																							</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<!-- Supprimer le domaine -->
																		<div class="modal" id="delete<?= $rowDomain[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/email.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Suppression du domaine</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<p>Voulez vous vraiment supprimer le domaine "<?= $rowDomain[$i]['domain'] ?>" ?</p>
																							<p> <font color="orange"><i class="fa fa-exclamation-triangle"></i></font> Toutes les adresses mail et les alias liés au domaine seront supprimés ! </p>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="delete" readonly />
																							<input type="hidden" name="id" value="<?= $rowDomain[$i]['id'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Supprimer</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<?php
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
									
									<div id="icon-createzone" class="tab-pane fade" role="tabpanel" aria-labelledby="createzone-tab">
									
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="row">
															<div class="col-md-12">
																<div class="card-body">
																	<h4 class="card-title"><center></center></h4><hr>
																	<form action="scripts/pages/email.php" method="POST">
																		<div class="col-md-12">
																			<div class="row">
																				
																				<div class="col-md-5">
																					<div class="form-group">
																						<label>Sous domaine (WebMail)</label>
																						<input class="form-control" type="text" name="subdomain" placeholder="roundcube" required />
																					</div>
																				</div>
																				<div class="col-md-5">
																					<div class="form-group">
																						<label>Domaine</label>
																						<input class="form-control" type="text" name="domain" placeholder="foxmie" required />
																					</div>
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label>Extension</label>
																						<select class="form-control" name="extension" required>
																							<?php
																								
																								$database->query("SELECT * FROM bindextension"); 
																								$rowBindExtension = $database->resultset();
																	
																								$maxBindExtension = $database->rowCount();
																								for ($i=0; $i<$maxBindExtension; $i++){
																								
																							?>
																							<option value="<?= $rowBindExtension[$i]['extension'] ?>"><?= $rowBindExtension[$i]['extension'] ?></option>
																							<?php
																								}
																							?>
																						</select>
																					</div>
																				</div>
																				
																			</div>
																		</div>
																		<input type="hidden" name="action" value="create" />
																		<button type="submit" class="btn btn-primary btn-block">Configurer le domaine</button>
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