<?php
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);

//extract($_REQUEST);

	$sql = "SELECT * FROM `user_master` WHERE  `username`='$username'";
	$rsd = mysql_query($sql);
	$res = mysql_num_rows($rsd); //returns 0 if not already exist 
	
	if($res > 0) {
		echo "no";
	}else {
		echo "yes";
	}

?>