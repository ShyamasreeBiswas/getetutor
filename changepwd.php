<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS.'class.userlogin.php');
require_once(PATH_TO_CLASS."class.utility.php");
require_once(PATH_TO_CLASS.'class.log.php');
require("utils.php");

$utility = new utility();
$log_details= new log_details();

if($_SESSION['username']=='') {  		
	header('location: index.php');
	exit;
}
if($utility->cleanData($_POST['mode'])=="change_pwd") {
	$userlogin 				= new userlogin();
	$userlogin->password	= $utility->cleanData($_POST['password']);
	$userlogin->new_pwd 	= $utility->cleanData($_POST['new_password']);

	if($userlogin->userChangePassword()) 
	{
		$log_details->user_id 	= $_SESSION['id'];
		$log_details->event 	= "Changed password";
		$log_details->ip_address= $_SESSION['ip_address'];
		$log_details->save(0);
		
		$GLOBALS['msg'] = "Password Updated Successfully.";
	} 
	else 
	{
		$GLOBALS['msg'] = "Password Mismatch.";
	}
	
	$_POST['mode']=='';
	disphtml("main();");
	
} else if($utility->cleanData($_POST['mode'])=="cancel") { 		
	header('location: main.php');
	exit;
} else {
	disphtml("main();");
}

ob_end_flush();

function main() {
?>


<script language="javascript">
$(document).ready(function() {
	$("#change_pwd").validationEngine();
});
</script>

<form name="change_pwd" id="change_pwd" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<input type="hidden" name="mode" value="change_pwd">
	<table width="80%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
		<tr class="TDHEAD">
			<td colspan="3">Change Password</td>
		</tr>
		<tr>
			<td class="ERR" colspan="3" align="left"><?php echo $GLOBALS['msg'];?></td>
		</tr>
        <tr> 
            <td colspan="3" align="right"><b><font color="#FF0000">All * marked fields are mandatory </font></b></td>
        </tr>
        <tr> 
            <td colspan="3" align="right"></td>
        </tr>
		<tr>
			<td align="right" valign="top" class="tbllogin" width="30%"><font color="#FF0000">*</font>Old Password</td>
			<td align="center" valign="top" class="tbllogin" width="5%">:</td>
			<td  align="left" valign="top"><input type="password" name="password" id="old_password" value="" class="validate[required] inplogin" ></td>
		</tr>
		<tr>
			<td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>New Password</td>
			<td align="center" valign="top" class="tbllogin">:</td>
			<td  align="left" valign="top"><input type="password" name="new_password" id="new_password" value="" class="validate[required] inplogin"></td>
		</tr>
		<tr>
			<td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Confirm New Password</td>
			<td align="center" valign="top" class="tbllogin">:</td>
			<td align="left" valign="top"><input type="password" name="conf_new_password" id="conf_new_password" value="" class="validate[required,equals[new_password]] inplogin"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" value="Change" class="button"> &nbsp;&nbsp; <input type="button" name="btn" value="Cancel" onClick="f_cancel()" class="button"></td>
		</tr>
	</table>
</form>
<table><tr><td height="120px">&nbsp;</td></tr></table>
<?php } ?>