<?php
// Empty configuration for password
// See /usr/share/roundcube/plugins/password/config.inc.php.dist for instructions
// Check the access right of the file if you put sensitive information in it.
//$config=array();

$config['password_driver'] = 'sql';
$config['password_confirm_current'] = true;
$config['password_minimum_length'] = 6;
$config['password_require_nonalpha'] = true;
$config['password_log'] = false;
$config['password_login_exceptions'] = null;
$config['password_hosts'] = array('localhost');
$config['password_force_save'] = true;

// SQL Driver options
$config['password__db_dsn'] = 'mysql://roundcube:@localhost/roundcubemail';
 
// SQL Update Query with encrypted password using random 8 character salt
$config['password_query'] = 'UPDATE postfix.addresses SET pwd=ENCRYPT(%p, CONCAT(\'$6$\',SUBSTRING((SHA(RAND())), -16))) WHERE email=%u LIMIT 1';

?>
