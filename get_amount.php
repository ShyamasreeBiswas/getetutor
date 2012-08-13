<?php
ob_start();
define("ROOT_PATH","../");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."admin/admin_utils.php");
include_once(PATH_TO_CLASS."class.users.php");
include_once(PATH_TO_CLASS."class.message.php");
include_once(PATH_TO_CLASS."class.utility.php");
include_once(PATH_TO_CLASS."class.payments.php");
include_once(PATH_TO_CLASS."class.costumes.php");
include_once(PATH_TO_CLASS."class.database.php");
require_once(INCLUDES_PATH."chksession.php");

$utility 	= new utility();
$users		= new users();
$payments 	= new payments();
$costumes	= new costumes();
$database	= new database();
$message 	= new message();

if($_SESSION['admin_name']=='') {
	header('Location: index.php');
	exit();
}

echo $costume_id = $_REQUEST['id'];

?>

