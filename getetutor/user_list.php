<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.users.php");
require_once(PATH_TO_CLASS.'class.log.php');	
include_once(PATH_TO_CLASS."class.utility.php");
require_once(INCLUDES_PATH."chksession.php");

$utility 	= new utility();
$users 		= new users();
$log_details= new log_details();

if($_SESSION['username']=='') {
	header('Location: index.php');
	exit();
}


$mode 		= $utility->cleanData($_POST['mode']);
$user_id 	= $utility->cleanData($_POST['user_id']);

if($mode=='view') 
{
	disphtml("showData(".$user_id.");");
} 
else if($mode=='add' || $mode=='edit') 
{
	if($user_id) 
	{} 
	else 
	{
		$user_id = -1;
	}
	
	disphtml("saveData(".$user_id.");");

} 
else if($mode =='change_status') 
{					  
  	$users->activeDeactive($user_id);

	$log_details->user_id 	= $_SESSION['id'];
	$log_details->event 	= "has changed status of an user";
	$log_details->ip_address= $_SESSION['ip_address'];
	$log_details->save(0);

  	$GLOBALS['admin_msg'] = $users->errors;
	disphtml("main();");
} 
else if($mode=="save") 
{	
	
	if($_POST['name']=="")
	{
		$GLOBALS['admin'] = "Name can not be blank";
		disphtml("main();");
		exit();
	}
	if($_POST['password']=="")
	{
		$GLOBALS['admin'] = "Password can not be blank";
		disphtml("main();");
		exit();
	}
	
	foreach($_POST as $key=>$val) 
	{
		if($key!='mode' && $key!='submit')	
		{
			$users->$key=$val;
		}
	}

	if($user_id>0) 
	{
		if($users->save($user_id)) 
		{
			$log_details->user_id 	= $_SESSION['id'];
			$log_details->event 	= "has updated an user";
			$log_details->ip_address= $_SESSION['ip_address'];
			$log_details->save(0);
			
			$GLOBALS['admin_msg'] = "User updated successfully";
			disphtml("main();");
		} else {
			$GLOBALS['admin_msg'] = $users->admin_msg;
		  	disphtml("saveData(".$user_id.");");
		}
	} 
	else 
	{
		if($users->save($user_id="NULL")) 
		{
			//echo "here"; die;
			$log_details->user_id 	= $_SESSION['id'];
			$log_details->event 	= "has created an user";
			$log_details->ip_address= $_SESSION['ip_address'];
			$log_details->save(0);

			$GLOBALS['admin_msg'] = "User inserted successfully";
			disphtml("main();");
		} 
		else 
		{
			$GLOBALS['admin_msg'] = $users->errors;
			$user_id=-1;
		  	disphtml("saveData($user_id);");
		}
	}
} 
else if($mode=='delete' && isset($user_id)) 
{
	$users->deleteData($user_id);
	
	$log_details->user_id 	= $_SESSION['id'];
	$log_details->event 	= "has deleted an user";
	$log_details->ip_address= $_SESSION['ip_address'];
	$log_details->save(0);


	$GLOBALS['admin_msg'] = "User deleted successfully";
	disphtml("main();");
} 
else 
{
	disphtml("main();");
}
	
ob_end_flush();

function main() 
{
	$utility 		= new utility();
	$users 			= new users();
	
	$user_type = $_SESSION['type'];
	
	$hold_page 		= $utility->cleanData($_POST['hold_page']);
	$orderType 		= $utility->cleanData($_POST['orderType']);
	$fieldName		= $utility->cleanData($_POST['fieldName']);
	$search_mode	= $utility->cleanData($_POST['search_mode']);
	$search_type 	= $utility->cleanData($_POST['search_type']);
	$txt_search		= $utility->cleanData($_POST['txt_search']);
	$txt_alpha		= $utility->cleanData($_POST['txt_alpha']);
	
	if($hold_page>0) {
		$GLOBALS['start'] = $hold_page;
	}
	
	if($mode == "refresh") {
		$member_row = $_POST;
	}
	
	if($orderType && $fieldName) {
		$orderType 	= $orderType=='ASC'?' ASC ':' DESC ';
		$orderBy	= 'ORDER BY '.$fieldName.$orderType;
	} else {
		$orderBy='ORDER BY id DESC';
	}
	
	if ($search_mode=="ALPHA") {
		$member_sql = " SELECT	* 
						FROM 	".$users->table_name."
						WHERE 	id !=1 
						AND 	name  LIKE '".$txt_alpha."%' ".$orderBy." 
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
		$row=$users->search(" SELECT COUNT(*) FROM ".$users->table_name." WHERE id !=1  AND name LIKE '".$txt_alpha."%' ");
		$count=$row[0][0];
	}
	
	if ($search_mode=="SEARCH") {
		$txt_search = trim($txt_search);
		$member_sql = "SELECT * FROM ".$users->table_name." WHERE id !=1 AND ";
		$member_row = "SELECT COUNT(*) FROM ".$users->table_name." WHERE id !=1 AND ";
		 
		if($search_type=='name') {
			$member_sql .=" name LIKE '".$txt_search."%'  ".$orderBy."";
			$member_row .=" name LIKE '".$txt_search."%'   ";
		} 
		
		$member_sql .= "  LIMIT ".$GLOBALS['start'].",".$GLOBALS['show'];
		$row		 = $users->search($member_row);
		$count		 = $row[0][0];
	}
	
	if ($search_mode=="") 
	{
		$member_sql = " SELECT 	* 
						FROM 	".$users->table_name."   WHERE id !=1 ".$orderBy."  
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
						
		$row=$users->search(" SELECT COUNT(*) FROM ".$users->table_name."  ");
		$count=$row[0][0];
	}
	
	$result=$users->search($member_sql);
?>
<form name="frmSearch" id="frmSearch" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode; ?>">
	<input type="hidden" name="txt_alpha"   id="txt_alpha" 	 value="<?php echo $txt_alpha;   ?>">
	
	<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="6">Members Search Panel</td>
    </tr>
	<tr class="content">
	  <td colspan="3"></td>
      <td class="text_normal">Search By</td>
      <td>:</td><td>
	  <select name="search_type" class="inplogin">
          <option value="">Select One</option>
          <option value="name"<?php echo $search_type=='name' ? 'selected' : '';?>>Name</option>
	  </select>
	&nbsp;&nbsp;&nbsp;&nbsp;
     <input name="txt_search" id="txt_search" type="text" class="textbox" value="<?php echo stripslashes($txt_search);?>">
	&nbsp;&nbsp;
    <input type="button" class="button" onClick="search_text()" value="Search">
	&nbsp;&nbsp;&nbsp;
    <input name="btnShowAll" type="button" class="button" value="Show All" onClick="javascript:show_all();">
		</td>
	</tr>
	<tr><td colspan="6" align="center"><?php $utility->DisplayAlphabet(); ?></td></tr>
</table>
</form>

<script language="JavaScript">
function show_all()
{
	document.frmSearch.search_mode.value = "";	
	document.frmSearch.txt_search.value  = "";
	document.frmSearch.search_type.value="";
	document.frmSearch.submit();	
}

function search_alpha(alpha)
{
	document.frmSearch.search_mode.value = "ALPHA";	
	document.frmSearch.txt_search.value = '';
	document.frmSearch.txt_alpha.value = alpha;
	document.frmSearch.submit();
}	

function search_text()
{
	if(document.frmSearch.search_type.value=="") {
		alert("Please Select A Search Type");
		document.frmSearch.search_type.focus();
		return false;
	}
	if(document.frmSearch.txt_search.value.search(/\S/)==-1)
	{
		alert("Please Enter Search Criteria");
		document.frmSearch.txt_search.focus();
		return false;
	}
	document.frmSearch.search_mode.value = "SEARCH";
	document.frmSearch.submit();
}

function OrderBy(order_type,field_name)
{
	document.frm_opts.fieldName.value=field_name;
	document.frm_opts.orderType.value=order_type;
	document.frm_opts.submit();
}

function ChangeStatus(ID,record_no)
{
	document.frm_opts.mode.value='change_status';
	document.frm_opts.user_id.value=ID;
	document.frm_opts.hold_page.value = record_no*1;
	document.frm_opts.submit();
}

function addData()
{
	document.frm_opts.mode.value='add';
	document.frm_opts.submit();
}

function viewData(user_id)
{
	document.frm_opts.mode.value='view';
	document.frm_opts.user_id.value=user_id;
	document.frm_opts.submit();
}

function editData(user_id)
{
    document.frm_opts.mode.value='edit';
	document.frm_opts.user_id.value=user_id;
	document.frm_opts.submit();
}

function deleteData(user_id)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true ) {
		document.frm_opts.mode.value='delete';
		document.frm_opts.user_id.value=user_id;
		document.frm_opts.submit();
	}
}
</script>
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr> 
		<td width="20%" align="center" class="ErrorText">Total records:<? echo $count;?></td>
		<td width="27%" align="center" class="ErrorText">No. of records to be shown:</td>
		<td width="4%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(10,20,30,'frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
		<td width="5%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
        <td width="5%" align="right"><a href="javascript:addData();" title=" Add User "><img border="0" src="images/plus_icon.gif"></a></td>
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Members Management</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="8%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="20%" ><div align="left"><a href="javascript:OrderBy('<?php echo $orderType=='ASC'?'DESC':'ASC'; ?>','name')" title="Sort By Name"> <strong>Name</strong></a><?php if($fieldName=='name'){?><img src="images/<?php echo $orderType=='ASC'?'arrowup.gif':'arrowdn.gif'; ?>"><?php }?></div></td>
	<td width="28%" ><strong>User Email</strong></td>
    <td width="28%" ><strong>Department</strong></td>
    <td width="10%" ><div align="center"><strong>Is Active</strong></div></td>
    <td width="5%"  ><div align="center"><strong>View</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Edit</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Delete</strong></div></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) {
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo  stripslashes($result[$i]['name']);?></td>
    <td valign="top" align="left"><?php echo stripslashes($result[$i]['email']);?> </td>
    <td valign="top" align="left"><?php echo stripslashes($result[$i]['department_id']);?> </td>
    <td valign="top" align="center"><a href="javascript:ChangeStatus(<?php echo $result[$i]['id'];?>,<?php echo $GLOBALS['start']; ?>)" title="<?php echo ($result[$i]['is_active']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['is_active']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a></td>
    <td align="center" valign="top"><a href="javascript:viewData( <?php echo $result[$i]['id'];?>);" title="View User Details"><img src="images/preview_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:editData( <?php echo $result[$i]['id'];?>);" title="Edit User Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:deleteData( <?php echo $result[$i]['id'];?>);" title="Delete User Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td>
  </tr>
  <?php 
	$i++;
	}
	
	if($i == 0) { 
	?>
  <tr> 
    <td align="center" colspan="9">No records found</td>
  </tr>
  <?php } ?>
</table>
	<?php 
	if($count>0 && $count > $GLOBALS['show']) {
	?>
	<table width="90%" align="center" border="0" cellpadding="5" cellspacing="2">
		<tr>
			<td><?php $utility->pagination($count,"frm_opts");?></td>
		</tr>
	</table>
	<?php } ?>
	
	<form name="frm_opts" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" >
		<input type="hidden" name="mode" value="">
		<input type="hidden" name="user_id">
		<input type="hidden" name="pageNo" 		value="<?php echo $pageNo;    ?>">
		<input type="hidden" name="pagePerNo" 	value="<?php echo $pagePerNo; ?>">
		<input type="hidden" name="url" 		value="add_user.php">
		<input type="hidden" name="search_type" value="<?php echo $search_type;?>">
		<input type="hidden" name="search_mode" value="<?php echo $search_mode;?>">
		<input type="hidden" name="txt_alpha" 	value="<?php echo $txt_alpha;?>">
		<input type="hidden" name="txt_search"  value="<?php echo $txt_search;?>">
		<input type="hidden" name="hold_page" 	value="">
		<input type="hidden" name="fieldName" 	value="<?php echo $fieldName;?>">
		<input type="hidden" name="orderType" 	value="<?php echo $orderType;?>">
	</form>
<table><tr><td height="82px">&nbsp;</td></tr></table>
<?php }

function showData($user_id)
{ 
	$users		= new users();
	if($user_id) 
	{
	  $users->showData($user_id);
	  
	}
?>
<script language="JavaScript">
function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);

        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);    // Not entirely sure if this is necessary
    form.submit();
}
</script>

  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
  <tr>
    <td align="center">
		<table width="100%" align="center" cellpadding="5" cellspacing="2">
          <tr class="TDHEAD"> 
            <td colspan="3" style="padding-left:10px;" class="text_main_header"> 
             View Member's Information</td>
          </tr>
          <tr> 
            <td width="26%" align="right" valign="top" class="tbllogin"> Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td align="left" valign="top"><?php echo  stripslashes($users->name); ?>
            </td>
          </tr>
          <tr> 
          <td align="right" valign="top" class="tbllogin">Username </td>
          <td align="center" valign="top" class="tbllogin">:</td>
          <td  align="left" valign="top"><?php echo stripslashes($users->username);?></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin">Email</td>
             <td align="center" valign="top" class="tbllogin">:</td>
            <td  align="left" valign="top"><?php echo stripslashes($users->email);?></td>
          </tr>
		  
		  <tr> 
            <td class="tbllogin" align="right" valign="top">Is Active</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top">
                <?php echo $users->is_active=='N' ? 'In Active' : 'Active';?>
            </td>
          </tr>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td class="point_txt"><input name="button" type="button" class="button" onClick="javascript:window.location='<?=$_SERVER['PHP_SELF']?>';"  value="Back"> 
            </td>
          </tr>
        </table>
	</td>
  </tr>
</table>
<table><tr><td height="170px">&nbsp;</td></tr></table>
<? 
 } /////////////// End of function showData()

function saveData($user_id)
{ 
	$users = new users();
	$utility = new utility();
	if($user_id>0)
    {
		$users->showData($user_id);
	}
			
?>
<script language="javascript">
function generate_password() 
{
	var password = $('#password').val();
	$.ajax({
		  url: "<?php ROOT_PATH;?>generate_password.php",
		  data: "",
		  cache: false,
		  success: function(html){ 
			$("#password").val(html);
		  }
	});
}

$(document).ready(function() {
	$("#frmUser").validationEngine();
});


</script>
<form name="frmUser" id="frmUser" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data"   >
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="user_id" value="<?php echo $user_id;?>" >
  <? if($GLOBALS['msg']!='') { ?>
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?=$GLOBALS['msg']?></td>
		</tr>
  </table>
  <? } $GLOBALS['msg']=''; ?>
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
            <td width="71%" align="left" valign="top"><input type="text" name="name" id="name" class="validate[required] inplogin" value="<?php echo stripslashes($users->name);?>"> 
            </td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Email</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="email" id="email" class="validate[required][email] inplogin" value="<?php echo stripslashes($users->email);?>"> 
            </td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Username</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="username" id="username" class="validate[required] inplogin" value="<?php echo stripslashes($users->username);?>"> 
            </td>
          </tr>
          <?php if($user_id < 1) {?>
		   <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Password</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="password" name="password" id="password" class="validate[required] inplogin" value="<? echo stripslashes($users->password);?>"> 
            </td>
          </tr>
		  <?php } ?>
			            <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td> <input name="submit" type="submit" class="button" value="<?php echo $user_id >0 ?'Update':'Add'?>"> 
              &nbsp; <input name="button" type="button" class="button" onClick="javascript:window.location='<?php echo $_SERVER['PHP_SELF'];?>';"  value="Cancel"> 
            </td>
          </tr>
        </table>
	</td>
  </tr>
</table>
</form>
<table><tr><td height="60px">&nbsp;</td></tr></table>
<? 
 } /////////////// End of function editData()
?>