<?php
//set_magic_quotes_runtime(0);

//////////////////////////// For Local //////////////////////////
$database 	= "getetutor";
$host 		= "localhost";
$user 		= "root";
$pwd 		= "";
define("URL","http://localhost/getetutor");

define('DB_USER', "$user");
define('DB_PWD', "$pwd");
define('DB_HOST', "$host");
define('DB_NAME', "$database");
define('CURRENCY',"$");	

require INCLUDES_PATH . ('dbconn.php');
require PATH_TO_CLASS . ('class.sessions.php');

$session = new Session();
?>