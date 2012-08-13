<?php
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);


$query = "SELECT * FROM groups LEFT JOIN group_members 
			ON groups.`id` = group_members.`group_id` WHERE (group_members.`user_id`='".$_SESSION['id']."' OR groups.`grp_created_by` = '".$_SESSION['id']."')";
?>