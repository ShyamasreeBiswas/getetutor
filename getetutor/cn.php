<?php
$cn = mysql_connect('localhost', 'root', '') or
	die('Unable to connect to server');
mysql_select_db('chat2', $cn) or
	die(mysql_error($cn));
?>