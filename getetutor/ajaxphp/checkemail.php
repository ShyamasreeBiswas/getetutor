<?php
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);

extract($_REQUEST);

if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email))
{
	$sql = "SELECT * FROM `user_master` WHERE  `email`='$email'";
	$rsd = mysql_query($sql);
	$msg = mysql_num_rows($rsd); //returns 0 if not already exist 
}
else
{
	$msg = "invalid";
}
echo $msg;
?>