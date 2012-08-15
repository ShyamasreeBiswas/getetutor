<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS.'class.userlogin.php');
require_once(PATH_TO_CLASS.'class.log.php');	
require_once(PATH_TO_CLASS."class.utility.php");
include_once(PATH_TO_CLASS."class.users.php");
include_once(PATH_TO_CLASS."class.departments.php");

require_once("utils.php");

$userlogin 	= new userlogin();
$users 		= new users();
$utility	= new utility();



$log_details= new log_details();

$mode 		= $utility->cleanData($_POST['mode']);

if($mode=="add") 
{	
	
	foreach($_POST as $key=>$val) 
	{
		if($key!='mode' && $key!='submit')	
		{
			$users->$key=$val;
		}
	}
	
	$users->save($user_id="NULL");		
	$log_details->user_id 	= $_SESSION['id'];
	$log_details->event 	= "has created an user";
	$log_details->ip_address= $_SESSION['ip_address'];
	$log_details->save(0);
	
	$_SESSION['msg1'] = "You have successfully created your account. Wait for Admin approval.";
		
	disphtml("showLogin();");	
	
} 

if(isset($_POST['Login'])) 
{
	
	$password 	= $utility->cleanData($_POST['password']);
	$username	= $utility->cleanData($_POST['username']);
	
	if($userlogin->loginUser($username, $password)) 
	{
		$log_details->user_id 	= $_SESSION['id'];
		$log_details->event 	= "has logged in to the system";
		$log_details->ip_address= $_SESSION['ip_address'];
		$log_details->save(0);
		
		if($_SESSION['pageName']=='') 
		{
			header("Location:".ROOT_PATH."main.php"); 
			exit;
		}
		else 
		{
			header("Location:".$_SESSION['pageName']); 
			exit;
		}
	} 
	else 
	{
		$_SESSION['msg'] =  "Invalid login or password.";
		disphtml("showLogin();");
	}
} elseif($utility->cleanData($_POST['mode'])=="logout") {

	if(!empty($_SESSION))
	{
		$log_details->user_id 	= $_SESSION['id'];
		$log_details->event 	= "has logged off from the system";
		$log_details->ip_address= $_SESSION['ip_address'];
		
		$log_details->save(0);
	}

	$userlogin->logoutUser();
	$_SESSION['msg']	= "You are logged out.";
	disphtml("showLogin();");
} else {  	
	disphtml("showLogin();");
}
ob_end_flush();

function showLogin()
{
	if($_SESSION['username']!="") {
		header("Location: main.php");
		exit;
	}
	
?>
<script language="JavaScript">

$(document).ready(function() {
	$("#frmUser").validationEngine();
});

<!--code for email exist check start-->

$(document).ready(function(){
	$('#submit').attr('disabled','');
	var emailok = false;
	var boxes = $(".inplogin");
	var myForm = $("#frmUser"), email = $("#email"), emailInfo = $("#emailInfo");
	
	
	
	//Form Validation
	myForm.submit(function(){
		
		if(!emailok)
		{
			alert("Check Email");
			email.attr("value","");
			email.focus();
			return false;
		}
	});
	
	//send ajax request to check email
	email.blur(function(){
		$.ajax({
			type: "POST",
			data: "email="+$(this).attr("value"),
			url: "ajaxphp/checkemail.php",
			beforeSend: function(){
				emailInfo.html("<font color='blue'>Checking Email...</font>");
			},
			success: function(data){
				
				if(data == "invalid")
				{
					emailok = false;
					emailInfo.html("<font color='red'>Inavlid Email</font>");
				}
				else if(data != "0")
				{
					emailok = false;
					emailInfo.html("<font color='red'>Email Already Exist</font>");
				}
				else
				{
					emailok = true;
					emailInfo.html("<font color='green'>Email OK</font>");
				}
			}
		});
	});
});

<!--code for email exist check end-->
function check()
{
	if (document.frm_login.username.value.search(/\S/)==-1) {
		alert('Please enter your Login name');
		document.frm_login.username.focus();
		return false;
	}
	if (document.frm_login.password.value.search(/\S/)==-1) {
		alert('Please enter your password.');
		document.frm_login.password.focus();
		return false;
	}
	return true;
}
</script>

<script language="javascript">

function showCourse(str, crs)
{
	if (str=="")
	{
		document.getElementById("txtHint1").innerHTML="";
		return;
	}
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//alert(xmlhttp.responseText);
			document.getElementById("txtHint1").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajaxphp/getcourse.php?q="+str+"&s="+crs,true);
	xmlhttp.send();

}


</script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
jQuery(document).ready(function(){
	$(".various1").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});
});

</script>

<script language="javascript">

function pwdRecover(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","ajaxphp/pwdrecover.php?q="+str,true);
xmlhttp.send();
}


</script>

<table width="100%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
	<tr><td width="23%">
	<form name="frm_login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" onSubmit="return check();">
	<table width="25%" cellpadding="5" cellspacing="0" border="0" align="left" class="border">
		<tr>
			<td class="TDHEAD" colspan="3">Login</td>
		</tr>
		<tr>
			<td align="center" colspan="3" style="color:#FF0000;"><?php echo $_SESSION['msg']; $_SESSION['msg']='';?></td>
		</tr>
		<tr>
			<td>Login</td>
			<td>:</td>
			<td><input name="username" dir="username" type="text" value="" maxlength="15" class="inplogin" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td>:</td>
			<td><input name="password" id="password" type="password" value="" maxlength="12" class="inplogin" /></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td>
            <input type="submit" name="Login" value=" Login " class="inplogin">
            <br /> 
            <a  class="various1" href="#inline">Forgot Password?</a>
            <div style="display:none;">
            <div id="inline" style="width:350px;height:150px;overflow:auto;">
            	<div align="center" style="color:#006600; font-weight:bold;">Recover Password</div>
                
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #999;">
                
                	
                    <tr>
                        <td style="background:#F0F0F0;" align="center" id="txtHint">
                        <strong>Enter email address to recover password</strong>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        Email:&nbsp;<input type="text" name="emailpwd" class="inplogin" id="emailpwd" value="">
                        </td>
                    </tr>
                     <tr>
                        <td style="background:#F0F0F0;" align="center">
                        	<input type="button" name="pwdrecov" value="Submit" class="button" onclick="pwdRecover(emailpwd.value)"/>
                        </td>
                    </tr>
                                               
                </table>
                
            </div>
        </div>
            </td>
		</tr>
	</table>
    
    </form>
    </td>
    	
     <td width="77%">
     
     <?php if($_SESSION['msg1']!='') { ?>
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?php echo $_SESSION['msg1'];?></td>
		</tr>
  </table>
  <?php } $_SESSION['msg1']=''; ?>
  
     <?php if($_GET['page']=='register') {?>
     

   <table width="834">
		<tr>
			<td height="150">
            
<form name="frmUser" id="frmUser" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data"   >
  <input type="hidden" name="mode" value="add">
  
  
  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>&nbsp;</td></tr></table>
  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
  <tr>
    <td align="center">
		<table width="100%" align="center" cellpadding="5" cellspacing="2">
          <tr class="TDHEAD"> 
            <td colspan="3" style="padding-left:10px;" class="text_main_header"> 
              <?php echo ucwords($_POST['mode']);?>
              User Information</td>
          </tr>
          <tr> 
            <td colspan="3" align="right"><b><font color="#FF0000">All * marked 
              fields are mandatory</font></b></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="name" id="name" class="validate[required] inplogin" value=""> 
            </td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Email</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="email" id="email" class="validate[required,custom[email]] inplogin" value=""> &nbsp;&nbsp;<div id="emailInfo" align="left"></div>
            </td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Username</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="username" id="username" class="validate[required] inplogin" value=""> 
            </td>
          </tr>
          
		   <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Password</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="password" name="password" id="password" class="validate[required] inplogin" value="">
            </td>
          </tr>	  
		   
          <tr> 
            <td class="tbllogin" align="right" valign="top">Create Account As :</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top"> 
            	<input type="radio" name="type" value="T" />Tutor
                <input type="radio" name="type" value="S" checked="checked"/>Student
            </td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin">Select Department</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top">
			<?php
				$query_dept = "select * from departments where `status`='Y'";
				$row_dept = mysql_query($query_dept);
		?>
		  <select name="department_id" onChange="showCourse(this.value, '')"> 
			<option value="">---Select----</option>	
				<?php while($res_dept = mysql_fetch_array($row_dept)) {?>
				<option value="<?php echo $res_dept['id'];?>" <?php if($res_dept['id']==$groups->department_id) { ?> selected="selected" <?php }?>><?php echo $res_dept['name'];?></option>
				<?php }?>
		  </select>
            </td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin">Select Course</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top" id="txtHint1" class="course_class">
			<select name="course_id">
				<option value="">---Select----</option>					
  			</select> 
            </td>
          </tr>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td> <input name="submit" id="submit" type="submit" class="button" value="Register"> 
              &nbsp; <input name="button" type="button" class="button" onClick="javascript:window.location='<?php echo $_SERVER['PHP_SELF'];?>';"  value="Cancel"> 
            </td>
          </tr>
        </table>
	</td>
  </tr>
</table>
</form>
            </td>
		</tr>
	</table>
    <?php }?>
     </td>
    </tr>
    
    </table>
		
	

<?php } ?>