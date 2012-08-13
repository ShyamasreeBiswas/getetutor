<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS.'class.userlogin.php');
require_once(PATH_TO_CLASS.'class.users.php');
require_once(PATH_TO_CLASS."class.utility.php");
require_once(PATH_TO_CLASS.'class.log.php');
require("utils.php");

$utility = new utility();
$users = new users();
$log_details= new log_details();

if($_SESSION['username']=='') {  		
	header('location: index.php');
	exit;
}
if($utility->cleanData($_POST['mode'])=="change_my_account") {
	$userlogin 				= new userlogin();
	$userlogin->name		= $utility->cleanData($_POST['name']);
	$userlogin->email 		= $utility->cleanData($_POST['email']);

	if($userlogin->userChangeProfile()) 
	{
		$log_details->user_id 	= $_SESSION['id'];
		$log_details->event 	= "Profile Changed";
		$log_details->ip_address= $_SESSION['ip_address'];
		$log_details->save(0);
		
		$GLOBALS['msg'] = "Profile Updated Successfully.";
	} 
	else 
	{
		$GLOBALS['msg'] = "Please try again.";
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
$users = new users();

$sess_username	= $_SESSION['username'];

$user_sql = " SELECT 	* FROM 	".$users->table_name."  WHERE username = '".$sess_username."'";
$result=$users->search($user_sql);

$name	= $result[0]['name'];
$email	= $result[0]['email'];


?>


<script language="javascript">
$(document).ready(function() {
	$("#my_account").validationEngine();
});
</script>

<form name="my_account" id="my_account" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<input type="hidden" name="mode" value="change_my_account">
	<table width="80%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
		<tr class="TDHEAD">
			<td colspan="3">Change Profile</td>
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
			<td align="right" valign="top" class="tbllogin" width="30%"><font color="#FF0000">*</font>Name</td>
			<td align="center" valign="top" class="tbllogin" width="5%">:</td>
			<td  align="left" valign="top"><input type="text" name="name" id="name" value="<?php echo $name;?>" class="validate[required] inplogin" ></td>
		</tr>
		<tr>
			<td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Email</td>
			<td align="center" valign="top" class="tbllogin">:</td>
			<td  align="left" valign="top"><input type="text" name="email" id="email" value="<?php echo $email;?>" class="validate[required] inplogin"></td>
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