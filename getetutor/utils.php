<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.gc_divisor', 1);
//session_start();
header('Cache-Control: private');
require(INCLUDES_PATH."config.php");

if($_POST['pagePerNo'])
 $GLOBALS['show']=$_POST['pagePerNo'];
else
 $GLOBALS['show']=100;
 
if($_REQUEST['pageNo']=="")
{
	$GLOBALS['start'] = 0;
	$_REQUEST['pageNo'] = 1;
}
else
{
	$GLOBALS['start']=($_REQUEST['pageNo']-1) * $GLOBALS['show'];
}
function disphtml($what){?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITETITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="css/main.css" type="text/css">
<link href="css/validationEngine.jquery.css" rel="stylesheet" type="text/css" media="screen"  />
<script src="js/jquery.min.1.5.2.js" type="text/javascript"></script>
<script src="js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript"></script>
<script type="text/javascript" src="dtree.js"></script>
<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="check.js"></script>
<script language="JavaScript">
function logout()
{
	document.frm_logout.submit();
}

function showindex()
{
	 window.location = "index.php"
}
</script>

</head>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" bgcolor="#F1EFED">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#ffffff" style="border:1px solid #666666">
	<tr>
		<td bgcolor="#FFFFFF" colspan="2" align="center" height="30"  valign="middle"><?php include("top.php");?></td>
	</tr>
	<?php 
	if($_SESSION['username']=='')
	{
	?>
	<tr>
		<td height="55" colspan="2" align="center" valign="top"><?php eval($what);?></td>
  </tr>
	<?php
	}
	else
	{
	?>
	<TR><TD colspan="2" bgcolor="#FFFFFF"><img src="images/spacer.gif" width="1px" height="2px"></TD></TR>
	<tr>
		<td width="20%" align="left" valign="top"  class="border">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<TR>
					<TD width="1%">&nbsp;</TD>
					<TD width="98%"><?php include("left.php");?></TD>
					<TD width="1%">&nbsp;</TD>
				</TR>
			</table>
		</td>
		<td width="80%" valign="top" bgcolor="#FFFFFF"><br><?php echo eval($what);?></td>
	</tr>
	<?
	}
	?>
	<TR><TD bgcolor="#FFFFFF"><img src="images/spacer.gif" width="1px" height="2px"></TD></TR>
	<tr>
    <td colspan="2" align="center" height="15" bgcolor="#0EA3F1"> 
      <font color="#fffffF" size="1" face="Verdana">Copyright &copy; 
      <?=date('Y');?>
      - 
      <?=(date('Y')+1);?>
     GetETutor <br>Created By <strong>GetETutor</strong></font>
    </td>
	</tr>
</table>
<form name="frm_logout"	action="index.php" method="post" style="padding:0;margin:0;">
	<input name="mode" type="hidden" value="logout">
</form>
</body>
</html>
<?
database::Close(); 
} ////////////// End of function disphtml

?>