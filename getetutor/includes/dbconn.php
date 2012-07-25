<?php
function openAdminConn(&$db_conn) {
	if (isset($db_conn)) {
		return true;
	} else {
	   
		$db_conn = mysql_connect(DB_HOST, DB_USER, DB_PWD);
		if($db_conn)
			mysql_select_db(DB_NAME, $db_conn);
		else
		    die("Connection Failed");
		
	}
}

function openClientConn(&$db_conn) {
	if (isset($db_conn)) {
		return true;
	} else {
		$db_conn = mysql_connect(DB_HOST, DB_USER, DB_PWD);
		if($db_conn) {
			mysql_select_db(DB_NAME, $db_conn);
		} else {
			die("Connection Failed");
		}
	}
}

function closeConn(&$db_conn) {
	if (isset($db_conn)) mysql_close($db_conn);
}
?>