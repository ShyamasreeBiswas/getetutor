<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.getetutor.php");
include_once(PATH_TO_CLASS."class.course.php");
include_once(PATH_TO_CLASS."class.departments.php");
include_once(PATH_TO_CLASS."class.utility.php");
require_once(INCLUDES_PATH."chksession.php");

$utility 		= new utility();
$course 		= new course();
$departments 	= new departments();

if($_SESSION['username']=='') {
	header('Location: index.php');
	exit();
}

$mode	= $utility->cleanData($_POST['mode']);
$id 	= $utility->cleanData($_POST['id']);

disphtml("main();");

	
ob_end_flush();

function main() 
{
	$utility 		= new utility();
	$course		= new course();
	
?>

<link rel="stylesheet" type="text/css" href="css/global.css" />
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr> 
		<td></td>
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td>Course Management</td>
  </tr>
  <tr class="text_normal"> 
    <td>
    	<div class="chatBox">
	<div class="user">
    	<form name="signIn" action="" onsubmit="return false">
			<span class="error">Invalid username</span>
			<input type="text" size="13px" name="username" id="username" value="enter username" onclick='document.signIn.username.value = ""' />
			<input type="submit" id="signIn" value="SIGN IN" />
		</form>
		<?php //echo $_SESSION['username'];?>
	</div>
	<div class="main">
		
	</div>
	<div class="messageBox">
		<form name="messageBoxSignInForm" id="messageBoxSignInForm" onsubmit="return false">
			<input type="submit" id="messageBoxSignIn" value="Sign in to enter chat" />
		</form>
		<form name="newMessage" class="newMessage" action="" onsubmit="return false">
			<div class="left">
				<textarea name="newMessageContent" id="newMessageContent">Enter your message here</textarea>
			</div>
			<div class="right">
				<input type="submit" id="newMessageSend" value="SEND" />
			</div>
		</form>
	</div>
</div>
<script src="js/signinout.js" type="text/javascript"></script>
<script src="js/send_message.js" type="text/javascript"></script>
<script src="js/refresh_message_log.js" type="text/javascript"></script>
    </td>
  </tr>
 
</table>
	
	
<table><tr><td height="82px">&nbsp;</td></tr></table>
<?php } ?>
