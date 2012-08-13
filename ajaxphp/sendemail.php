<?php
//define("ROOT_PATH","");
//define("INCLUDES_PATH", ROOT_PATH."includes/");
//require_once("../includes/mailfunction.php"); 
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);

$q=$_GET["q"];
$s=$_GET["s"];
$g=$_GET["g"];

/////////getting emailid for group creator////////////////
$query_toid = "select * from user_master where `id`='".$q."'";
$row_toid = mysql_query($query_toid);
$res_toid = mysql_fetch_array($row_toid);
////////////getting emailid for session user//////////////////////
$query_frmid = "select * from user_master where `id`='".$s."'";
$row_frmid = mysql_query($query_frmid);
$res_frmid = mysql_fetch_array($row_frmid);
/////////////////////////////////////////////////////////////////

$sql_chk = "select * from group_members where `user_id`='".$s."' and `group_id`='".$g."'";
$row_chk = mysql_query($sql_chk);
$res_chk = mysql_num_rows($row_chk);

if($res_chk <= 0) {
	$qry = "insert into `group_members` (`user_id`, `group_id`, `request_date`) values ('".$s."', '".$g."', '".date("Y-m-d H:s:i")."')"; 
	mysql_query($qry);
}

//////////////////////////email content//////////////////////////////

$to 		= $res_toid['email'];
$fromEmail 	= $res_frmid['email'];
$subject	="Join Request";

$body="<table width=\"100%\" border=\"0\">
			<tr><td>&nbsp;</td></tr>					
		   
		   <tr><td>join request</td></tr>
		  <tr>
			<td>&nbsp;</td>
		  </tr>
		</table>";

$headers  		= "MIME-Version: 1.0\r\n";
$headers 		.= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers 		.= "From: Admin <".$fromEmail.">\r\n";
		
mail($to, $subject, $body, $headers);

echo "<font color=\"#FF0000\">Message Sent!</font>";
?>
