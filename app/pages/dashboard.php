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
	
	// Section system
	// Fonction de conversion
	function getTime($seconds)
    {
        $units = array(
            'year'   => 365*86400,
            'day'    => 86400,
            'hour'   => 3600,
            'minute' => 60,
            // 'second' => 1,
        );
     
        $parts = array();
     
        foreach ($units as $name => $divisor)
        {
            $div = floor($seconds / $divisor);
     
            if ($div == 0)
                continue;
            else
                if ($div == 1)
                    $parts[] = $div.' '.$name;
                else
                    $parts[] = $div.' '.$name.'s';
            $seconds %= $divisor;
        }
     
        $last = array_pop($parts);
     
        if (empty($parts))
            return $last;
        else
            return join(', ', $parts).' and '.$last;
    }
	
	// Nom du serveur
	$hostname = php_uname('n');

	// Systeme d'exploitation
	if (!($os = shell_exec('/usr/bin/lsb_release -ds | cut -d= -f2 | tr -d \'"\'')))
	{
		if(!($os = shell_exec('cat /etc/system-release | cut -d= -f2 | tr -d \'"\''))) 
		{
			if (!($os = shell_exec('find /etc/*-release -type f -exec cat {} \; | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\'')))
			{
				$os = '?';
			}
		}
	}
	$os = trim($os, '"');
	$os = str_replace("\n", '', $os);

	// Noyaux
	if (!($kernel = shell_exec('/bin/uname -r')))
	{
		$kernel = '?';
	}
	
	// Duree de fonctionnent
	if (!($totalSeconds = shell_exec('/usr/bin/cut -d. -f1 /proc/uptime')))
	{
		$uptime = '?';
	}
	else
	{
		$uptime = getTime($totalSeconds);
	}
	
	// Date du server
	if (!($server_date = shell_exec('/bin/date')))
	{
		$server_date = date('Y-m-d H:i:s');
	}
	
	// Construction du tableau
	$array = array(
		'hostname'      => $hostname,
		'os'            => $os,
		'kernel'        => $kernel,
		'uptime'        => $uptime,
		'server_date'   => $server_date,
	);
	
	$system = $array;
	
	// Section CPU
	// Fonction "Nombre de cores"
	function getCpuCores()
    {
        if (!($cpu_cores = shell_exec('/bin/grep -c ^processor /proc/cpuinfo')))
        {
            if (!($cpu_cores = trim(shell_exec('/usr/bin/nproc'))))
            {
                $cpu_cores = 1;
            }
        }

        if ((int)$cpu_cores <= 0)
            $cpu_cores = 1;

        return (int)$cpu_cores;
    }
	
	// Recuperation du nombre de cores
	$cpu_cores = getCpuCores();
	
	// Construction des variables
	$model      = '?';
	$frequency  = '?';
	$cache      = '?';
	$bogomips   = '?';
	
	// Recuperation des information du processeur
	if ($cpuinfo = shell_exec('cat /proc/cpuinfo'))
	{
		$processors = preg_split('/\s?\n\s?\n/', trim($cpuinfo));

		foreach ($processors as $processor)
		{
			$details = preg_split('/\n/', $processor, -1, PREG_SPLIT_NO_EMPTY);

			foreach ($details as $detail)
			{
				list($key, $value) = preg_split('/\s*:\s*/', trim($detail));

				switch (strtolower($key))
				{
					case 'model name':
					case 'cpu model':
					case 'cpu':
					case 'processor':
						$model = $value;
					break;

					case 'cpu mhz':
					case 'clock':
						$frequency = $value.' MHz';
					break;

					case 'cache size':
					case 'l2 cache':
						$cache = $value;
					break;

					case 'bogomips':
						$bogomips = $value;
					break;
				}
			}
		}
	}
	
	// Recuperation de la frequence du processeur
	if ($frequency == '?')
	{
		if ($f = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_max_freq'))
		{
			$f = $f / 1000;
			$frequency = $f.' MHz';
		}
	}
	
	// Construction du tableau
	$array = array(
		'model'      => $model,
		'num_cores'  => $cpu_cores,
		'frequency'  => $frequency,
		'cache'      => $cache,
		'bogomips'   => $bogomips,
	);
	
	$cpu = $array;
	
	// Section RAM
	// Fonction pour trouver l'unité
	function getSize($filesize, $precision = 2)
    {
        $units = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');

        foreach ($units as $idUnit => $unit)
        {
            if ($filesize > 1024)
                $filesize /= 1024;
            else
                break;
        }
        
        return round($filesize, $precision).' '.$units[$idUnit].'B';
    }
	
	// Initialisation des variables
	$free = 0;

	// Recuperation des infos RAM
	if (shell_exec('cat /proc/meminfo'))
	{
		$free    = shell_exec('grep MemFree /proc/meminfo | awk \'{print $2}\'');
		$buffers = shell_exec('grep Buffers /proc/meminfo | awk \'{print $2}\'');
		$cached  = shell_exec('grep Cached /proc/meminfo | awk \'{print $2}\'');

		$free = (int)$free + (int)$buffers + (int)$cached;
	}

	// RAM Total
	if (!($total = shell_exec('grep MemTotal /proc/meminfo | awk \'{print $2}\'')))
	{
		$total = 0;
	}

	// RAM utilise
	$used = $total - $free;

	// RAM utilise pourcentage
	$percent_used = 0;
	if ($total > 0)
		$percent_used = 100 - (round($free / $total * 100));

	// Construction du tableau
	$array = array(
		'used'          => getSize($used * 1024),
		'free'          => getSize($free * 1024),
		'total'         => getSize($total * 1024),
		'percent_used'  => $percent_used,
	);
	
	$memory = $array;
	
	// Section Charge CPU
	// Recuperation de la charge CPU
	if (!($load_tmp = shell_exec('cat /proc/loadavg | awk \'{print $1","$2","$3}\'')))
	{
		$load = array(0, 0, 0);
	}
	else
	{
		// Recuperation du nombre de cores
		$cores = getCpuCores();

		$load_exp = explode(',', $load_tmp);

		$load = array_map(
			function ($value, $cores) {
				$v = (int)($value * 100 / $cores);
				if ($v > 100)
					$v = 100;
				return $v;
			}, 
			$load_exp,
			array_fill(0, 3, $cores)
		);
	}

	$array = $load;
	$load_average = $array;
	
	// Section HDD
	// Recuperation des infos disk
	$percent_used = shell_exec("df -h / | grep -v Filesystem | awk '{print $5}' | sed 's/\%//'");
	$free = shell_exec("df -h / | grep -v Filesystem | awk '{print $4}' | sed 's/\%//'");
	$used = shell_exec("df -h / | grep -v Filesystem | awk '{print $3}' | sed 's/\%//'");
	$total = shell_exec("df -h / | grep -v Filesystem | awk '{print $2}' | sed 's/\%//'");
	
	// Initialisation du tableau
	$disk[] = array(
		'total'         => $total,
		'used'          => $used,
		'free'          => $free,
		'percent_used'  => $percent_used,
	);
	
	// Section Services
	$apache = fsockopen("localhost", 80, $errno, $errstr, 30);
	if($apache){
		$apache = 1;
	}else{
		$apache = 0;
	}
	
	$ssh = fsockopen("localhost", 22, $errno, $errstr, 30);
	if($ssh){
		$ssh = 1;
	}else{
		$ssh = 0;
	}
	
	$mysql = fsockopen("localhost", 3306, $errno, $errstr, 30);
	if($mysql){
		$mysql = 1;
	}else{
		$mysql = 0;
	}
	
?>			
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-body">
												<h4 class="card-title"><center>Dashboard</center></h4><hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['dashboard']['type']) and !empty($_SESSION['tmp']['dashboard']['msgbox']) ){
													
												?>	
												<div class="alert alert-<?= $_SESSION['tmp']['dashboard']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['dashboard']['msgbox'] ?> </center>
													</span>
												</div>
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['dashboard']['type']);
														unset($_SESSION['tmp']['dashboard']['msgbox']);
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
							<div class="col-md-12">	
								<div class="container-fluid">
									<div class="row">
										
										
										
										<div class="col-md-6">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
															<thead>
																<tr>
																	<th colspan="2">Système</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td><center>Hostname : </center></td>
																	<td><center><?= $system['hostname'] ?></center></td>
																</tr>
																<tr>
																	<td><center>OS : </center></td>
																	<td><center><?= $system['os'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Kernel : </center></td>
																	<td><center><?= $system['kernel'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Uptime : </center></td>
																	<td><center><?= $system['uptime'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Date : </center></td>
																	<td><center><?= $system['server_date'] ?></center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
															<thead>
																<tr>
																	<th colspan="2">CPU</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td><center>Model : </center></td>
																	<td><center><?= $cpu['model'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Cores : </center></td>
																	<td><center><?= $cpu['num_cores'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Frequency : </center></td>
																	<td><center><?= $cpu['frequency'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Bogomips : </center></td>
																	<td><center><?= $cpu['bogomips'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Cache : </center></td>
																	<td><center><?= $cpu['cache'] ?></center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
															<thead>
																<tr>
																	<th colspan="2">Memory</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td><center>Total : </center></td>
																	<td><center><?= $memory['total'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Utilisé : </center></td>
																	<td><center><?= $memory['used'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Pourcentage utilisé : </center></td>
																	<td><center><?= $memory['percent_used'] ?> %</center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
															<thead>
																<tr>
																	<th colspan="2">Load Average</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td><center>1 Min : </center></td>
																	<td><center><?= $load_average['0'] ?> %</center></td>
																</tr>
																<tr>
																	<td><center>5 Min : </center></td>
																	<td><center><?= $load_average['1'] ?> %</center></td>
																</tr>
																<tr>
																	<td><center>15 Min : </center></td>
																	<td><center><?= $load_average['2'] ?> %</center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
															<thead>
																<tr>
																	<th colspan="2">Disk</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td><center>Total : </center></td>
																	<td><center><?= $disk[0]['total'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Utilisé : </center></td>
																	<td><center><?= $disk[0]['used'] ?></center></td>
																</tr>
																<tr>
																	<td><center>Pourcentage utilisé : </center></td>
																	<td><center><?= $disk[0]['percent_used'] ?> %</center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
															<thead>
																<tr>
																	<th colspan="2">Services</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td><center>Apache</center></td>
																	<td><center><?php if ($apache == '1'){ echo '<font color="green">En ligne</font>'; }else{ echo '<font color="red">Hors ligne</font>'; } ?></center></td>
																</tr>
																<tr>
																	<td><center>OpenSSH</center></td>
																	<td><center><?php if ($ssh == '1'){ echo '<font color="green">En ligne</font>'; }else{ echo '<font color="red">Hors ligne</font>'; } ?></center></td>
																</tr>
																<tr>
																	<td><center>MariaDB</center></td>
																	<td><center><?php if ($mysql == '1'){ echo '<font color="green">En ligne</font>'; }else{ echo '<font color="red">Hors ligne</font>'; } ?></center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-12">
											<div class="card data-tables">
												<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
													<div class="toolbar"></div>
													<div class="fresh-datatables">
														<div class="row">
															<div class="col-md-6">
																<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
																	<tbody>
																		<tr>
																			<td><center>Service WEB</center></td>
																			<td><center><a href="scripts/services.php?service=1" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																		<tr>
																			<td><center>Service MySQL</center></td>
																			<td><center><a href="scripts/services.php?service=2" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																		<tr>
																			<td><center>Service Dovecot</center></td>
																			<td><center><a href="scripts/services.php?service=3" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																		<tr>
																			<td><center>Service Postfix</center></td>
																			<td><center><a href="scripts/services.php?service=4" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<div class="col-md-6">
																<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
																	<tbody>
																		<tr>
																			<td><center>Service Fail2Ban</center></td>
																			<td><center><a href="scripts/services.php?service=5" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																		<tr>
																			<td><center>Service SpamAssassin</center></td>
																			<td><center><a href="scripts/services.php?service=6" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																		<tr>
																			<td><center>Service ClamAV</center></td>
																			<td><center><a href="scripts/services.php?service=7" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																		<tr>
																			<td><center>Service Pare-feu</center></td>
																			<td><center><a href="scripts/services.php?service=8" class="btn btn-primary btn-block">Redémarrer</a></center></td>
																		</tr>
																	</tbody>
																</table>
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