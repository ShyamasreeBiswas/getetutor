<?php
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);

extract($_REQUEST);

if($code)
{
	$sql = "SELECT * FROM `departments` WHERE  `code`='$code'";
	$rsd = mysql_query($sql);
	$msg = mysql_num_rows($rsd); //returns 0 if not already exist 
	
}else if($name)
{
	$sql = "SELECT * FROM `departments` WHERE  `name`='$name'";
	$rsd = mysql_query($sql);
	$msg = mysql_num_rows($rsd); //returns 0 if not already exist 
}else
{
	$msg = "invalid";
}
echo $msg; die;
?>