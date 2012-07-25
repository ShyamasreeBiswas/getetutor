<?php
session_start();
header('Cache-Control: private');

require_once(INCLUDES_PATH."config.php");
require_once(PATH_TO_CLASS."class.database.php");

if($_POST['pagePerNo']) {
 $GLOBALS['show']=$_POST['pagePerNo'];
} else {
 $GLOBALS['show']=25;
} 

if($_REQUEST['pageNo']=="") {
	$GLOBALS['start'] 	= 0;
	$_REQUEST['pageNo'] = 1;
} else {
	$GLOBALS['start']=($_REQUEST['pageNo']-1) * $GLOBALS['show'];
}

function disphtml($what){
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITETITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/admin.css" type="text/css">
<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/message.css" type="text/css">
<script type="text/javascript" src="dtree.js"></script>
<script language="JavaScript">
function logout()
{
	document.frm_logout.submit();
}
</script>
</head>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" bgcolor="#F1EFED">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#FFFFFF" style=" margin: auto; border: 1px solid #666666">
	<tr>
		<td bgcolor="#FFFFFF" colspan="2" align="center"  valign="top">
			<?php include("admin_top.php");?>
		</td>
	</tr>
	<?php if($_SESSION['admin_name']=='') { ?>
	<tr>
		<td colspan="2" align="center" valign="top"><?php echo eval($what); ?></td>
	</tr> 
	<?php } else { ?>
	<tr valign="top">
		<td width="20%" align="left" valign="top"  class="border">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<TR>
					<TD width="1%">&nbsp;</TD>
					<TD width="98%" ><?php include("admin_left.php");?></TD>
					<TD width="1%">&nbsp;</TD>
				</TR>
			</table>
		</td>
		<td width="80%" valign="top" bgcolor="#FFFFFF"><?php echo eval($what);?></td>
	</tr>
	<?php } ?>
	<TR><TD bgcolor="#FFFFFF"><img src="images/spacer.gif" width="1px" height="2px"></TD></TR>
	<tr>
		<td colspan="2" valign="bottom" align="center" height="20" bgcolor="#1A449B"> 
		  <font color="#fffffF" size="1" face="Verdana">Copyright &copy; 
		  <?php echo date('Y');?> - <?php echo (date('Y')+1);?>
		  www.subastralinc.com - All Rights Reserved
		  </font> 
		  <br>
		</td>
	</tr>
</table>
<form name="frm_logout"	action="index.php" method="post" style="padding:0;margin:0;">
	<input name="mode" type="hidden" value="logout">
</form>
</body>
</html>
<? } ?>
