<?php
ob_start();
define("ROOT_PATH","../");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS."class.database.php");
require_once("admin_utils.php");

if($_SESSION['admin_name']!='') {
	disphtml("main();");
} else {
	header('location: index.php');
	exit;
}
ob_end_flush();

function main(){
?>
<link rel="stylesheet" href="css/admin.css" type="text/css">
<BR>	
<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
	<tr class="TDHEAD"> 
    	<td>Opps!!!</td>
	</tr>
	<tr>
    	<td class="text_normal_big">An error occuered during process.</td>
	</tr>
</table>
<?php }?>
