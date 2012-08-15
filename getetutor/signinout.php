<?php
require_once 'cn.php';
require_once 'protect.php';	

$username = protect($_POST['username']);
$time = time();

$sql = 'INSERT INTO users
	(username, join_date)
		VALUES
	("' . $username . '", ' . $time . ')';
$result = mysql_query($sql, $cn) or
	die(mysql_error($cn));
?>