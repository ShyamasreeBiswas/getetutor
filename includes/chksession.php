<?php 
//define("ROOT_PATH","../");
////define("INCLUDES_PATH", ROOT_PATH."includes/");
//define("PATH_TO_CLASS", ROOT_PATH."class/");
//include(PATH_TO_CLASS."class.session.php");
//session_start();
//$Session = new Session;
#include("connection.php");
//$_SESSION['pageName']=$_SERVER['REQUEST_URI'];
$_SESSION['pageName'] = $_SERVER['REQUEST_URI'];
if(!isset($_SESSION['username'])){
header("Location:" .  ROOT_PATH . "admin/index.php");
}
?>