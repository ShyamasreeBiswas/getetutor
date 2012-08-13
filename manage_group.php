<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.users.php");
require_once(PATH_TO_CLASS.'class.log.php');	
include_once(PATH_TO_CLASS."class.utility.php");
include_once(PATH_TO_CLASS."class.departments.php");
include_once(PATH_TO_CLASS."class.course.php");
include_once(PATH_TO_CLASS."class.groups.php");
require_once(INCLUDES_PATH."chksession.php");


$utility 		= new utility();
$users 			= new users();
$log_details	= new log_details();
$departments 	= new departments();
$course 		= new course();
$groups 		= new groups();

if($_SESSION['username']=='') {
	header('Location: index.php');
	exit();
}


$mode 		= $utility->cleanData($_POST['mode']);
$id 		= $utility->cleanData($_POST['id']);

if($mode=='view') 
{
	disphtml("showData(".$id.");");
} 
else if($mode=='add' || $mode=='edit') 
{
	if($id) 
	{} 
	else 
	{
		$id = -1;
	}
	
	disphtml("saveData(".$id.");");

} 
else if($mode =='change_status') 
{					  
  	$groups->activeDeactive($id);
  	$GLOBALS['admin_msg'] = $groups->errors;
	disphtml("main();");
} 
else if($mode=="save") 
{	
	
	foreach($_POST as $key=>$val) 
	{
		if($key!='mode' && $key!='submit')	
		{
			$groups->$key=$val;
		}
	}

	if($id>0) 
	{
		if($groups->save($id)) 
		{
			$GLOBALS['admin_msg'] = "Group updated successfully";
			disphtml("main();");
		} else {
			$GLOBALS['admin_msg'] = $groups->admin_msg;
		  	disphtml("saveData(".$id.");");
		}
	} 
	else 
	{
		if($groups->save($id="NULL")) 
		{
			$GLOBALS['admin_msg'] = "Group inserted successfully";
			disphtml("main();");
		} 
		else 
		{
			$GLOBALS['admin_msg'] = $groups->errors;
			$id=-1;
		  	disphtml("saveData($id);");
		}
	}
} 
else if($mode=='delete' && isset($id)) 
{
	$groups->deleteData($id);
	$GLOBALS['admin_msg'] = "Group deleted successfully";
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
	$departments 	= new departments();
	$course 		= new course();
	$groups 		= new groups();
	$users 			= new users();
	
	$user_type = $_SESSION['type'];
	
	$hold_page 		= $utility->cleanData($_POST['hold_page']);
	$orderType 		= $utility->cleanData($_POST['orderType']);
	$fieldName		= $utility->cleanData($_POST['fieldName']);
	$search_mode	= $utility->cleanData($_POST['search_mode']);
	$department_id 	= $utility->cleanData($_POST['department_id']);
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
						FROM 	".$groups->table_name."
						WHERE 	grp_name  LIKE '".$txt_alpha."%' ".$orderBy." 
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
		$row=$groups->search(" SELECT COUNT(*) FROM ".$groups->table_name." WHERE grp_name LIKE '".$txt_alpha."%' ");
		$count=$row[0][0];
	}
	
	if ($search_mode=="SEARCH") {
		
		$txt_search = trim($txt_search);
		
		$member_sql = "SELECT * FROM ".$groups->table_name." WHERE ";
		$member_row = "SELECT COUNT(*) FROM ".$groups->table_name." WHERE ";
		 
		if($department_id!='' && $txt_search!='') {
			$member_sql .=" department_id = '".$department_id."' AND grp_name LIKE '".$txt_search."%' ".$orderBy."";
			$member_row .=" department_id = '".$department_id."' AND grp_name LIKE '".$txt_search."%' ";
			
		}else if($department_id!='' && $txt_search==''){
			$member_sql .=" department_id = '".$department_id."' ".$orderBy."";
			$member_row .=" department_id = '".$department_id."' ";
			
		}else if($department_id=='' && $txt_search!=''){
			$member_sql .=" grp_name LIKE '".$txt_search."%' ".$orderBy."";
			$member_row .=" grp_name LIKE '".$txt_search."%' ";
			
		}
		
		$member_sql .= "  LIMIT ".$GLOBALS['start'].",".$GLOBALS['show'];
		$row		 = $groups->search($member_row);
		$count		 = $row[0][0];
	}
	
	if ($search_mode=="") 
	{
		
		$member_sql = " SELECT 	* 
						FROM 	".$groups->table_name." ".$orderBy."  
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
		//echo $member_sql; die;				
		$row=$groups->search(" SELECT COUNT(*) FROM ".$groups->table_name."  ");
		$count=$row[0][0];
	}
	
	$result=$groups->search($member_sql);
	
	
?>
<form name="frmSearch" id="frmSearch" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode; ?>">
	<input type="hidden" name="txt_alpha"   id="txt_alpha" 	 value="<?php echo $txt_alpha;   ?>">
	
	<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="6">Group Search Panel</td>
    </tr>
	<tr class="content">
	  <td colspan="3"></td>
      <td class="text_normal">Search By--</td>
      <td align="right">Deparment Name:</td><td>
	 
      
      <?php
			$query_dept = "select * from departments where `status`='Y'";
			$row_dept = mysql_query($query_dept);
	?>
     <select name="department_id"> 
        <option value="">Select One</option>	
            <?php while($res_dept = mysql_fetch_array($row_dept)) {?>
            <option value="<?php echo $res_dept['id'];?>"><?php echo $res_dept['name'];?></option>
            <?php }?>
      </select>
	&nbsp;&nbsp;
    Group Name:
    &nbsp;&nbsp;
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
	document.frmSearch.department_id.value="";
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
	if(document.frmSearch.department_id.value=="" && document.frmSearch.txt_search.value.search(/\S/)==-1) {
		alert("Please Select A Department / Enter Search Criteria");
		document.frmSearch.department_id.focus();
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
	document.frm_opts.id.value=ID;
	document.frm_opts.hold_page.value = record_no*1;
	document.frm_opts.submit();
}

function addData()
{
	document.frm_opts.mode.value='add';
	document.frm_opts.submit();
}

function viewData(id)
{
	document.frm_opts.mode.value='view';
	document.frm_opts.id.value=id;
	document.frm_opts.submit();
}

function editData(id)
{
    document.frm_opts.mode.value='edit';
	document.frm_opts.id.value=id;
	document.frm_opts.submit();
}

function deleteData(id)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true ) {
		document.frm_opts.mode.value='delete';
		document.frm_opts.id.value=id;
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
        <td width="5%" align="right"><a href="javascript:addData();" title=" Create Group "><img border="0" src="images/plus_icon.gif"></a></td>
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Group Management</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="8%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="20%" ><div align="left"><a href="javascript:OrderBy('<?php echo $orderType=='ASC'?'DESC':'ASC'; ?>','name')" title="Sort By Name"> <strong>Group Nmae</strong></a><?php if($fieldName=='name'){?><img src="images/<?php echo $orderType=='ASC'?'arrowup.gif':'arrowdn.gif'; ?>"><?php }?></div></td>
    <td width="28%" align="center"><strong>Department</strong></td>
    <td width="28%" align="center"><strong>Course</strong></td>
    <td width="28%" ><strong>Created By</strong></td>
    <td width="10%" ><div align="center"><strong>Is Active</strong></div></td>
    <td width="5%"  ><div align="center"><strong>View</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Edit</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Delete</strong></div></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) {
		
		$dept_name 	 = $departments->getDepartmentName($result[$i]['department_id']);
		if($result[$i]['course_id']!=0) {
			$course_name = $course->getCourseName($result[$i]['course_id']);
		}
		$user_name 	 = $users->getName($result[$i]['grp_created_by']);
		
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"> 
     <?php if($_SESSION['type']=='SUP' || $_SESSION['id']==$result[$i]['grp_created_by']) {?>
    <a href="manage_grppost.php?grp_id=<?php echo $result[$i]['id'];?>" title="See Group Activity"><?php echo  stripslashes($result[$i]['grp_name']);?></a>
    <?php }else {
		$sqlgrm = "select * from group_members where group_id = '".$result[$i]['id']."' and user_id = '".$_SESSION['id']."' and request_status = '0' and is_active = 'Y'";
		$rowgrm = mysql_query($sqlgrm);
		$resgrm = mysql_num_rows($rowgrm);
		
		if($resgrm > 0) {
		?>
        	<a href="manage_grppost.php?grp_id=<?php echo $result[$i]['id'];?>" title="See Group Activity"><?php echo  stripslashes($result[$i]['grp_name']);?></a>
            <?php }else {?>
    	<?php echo  stripslashes($result[$i]['grp_name']);?>
    <?php }
	}?>
    </td>
    <td valign="top" align="center"><?php echo $dept_name;?> </td>
     <td valign="top" align="center">
	 <?php if($result[$i]['course_id']!=0) {echo $course_name;}else {echo "----";}?> 
     </td>
    <td valign="top" align="left"><?php echo $user_name;?> </td>
    <td valign="top" align="center">
    <?php if($_SESSION['type']=='SUP' || $_SESSION['id']==$result[$i]['grp_created_by']) {?>
    <a href="javascript:ChangeStatus(<?php echo $result[$i]['id'];?>,<?php echo $GLOBALS['start']; ?>)" title="<?php echo ($result[$i]['is_active']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['is_active']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a>
      <?php }else {?>N/A <?php }?></td>
    <td align="center" valign="top"><a href="javascript:viewData( <?php echo $result[$i]['id'];?>);" title="Join & View Group"><img src="images/preview_icon.gif" border="0"></a></td>
    <td align="center" valign="top">
    	 <?php if($_SESSION['type']=='SUP' || $_SESSION['id']==$result[$i]['grp_created_by']) {?>
        <a href="javascript:editData( <?php echo $result[$i]['id'];?>);" title="Edit Group"><img src="images/edit_icon.gif" border="0"></a>
       <?php }else {?>N/A <?php }?>  
     </td>
    <td align="center" valign="top">
      <?php if($_SESSION['type']=='SUP' || $_SESSION['id']==$result[$i]['grp_created_by']) {?>
    <a href="javascript:deleteData( <?php echo $result[$i]['id'];?>);" title="Delete Group"><img name="xx" src="images/delete_icon.gif" border="0"></a>
    <?php }else {?>N/A <?php }?> 
    </td>
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
		<input type="hidden" name="id">
		<input type="hidden" name="pageNo" 		value="<?php echo $pageNo;    ?>">
		<input type="hidden" name="pagePerNo" 	value="<?php echo $pagePerNo; ?>">
		<input type="hidden" name="url" 		value="add_user.php">
		<input type="hidden" name="department_id" value="<?php echo $department_id;?>">
		<input type="hidden" name="search_mode" value="<?php echo $search_mode;?>">
		<input type="hidden" name="txt_alpha" 	value="<?php echo $txt_alpha;?>">
		<input type="hidden" name="txt_search"  value="<?php echo $txt_search;?>">
		<input type="hidden" name="hold_page" 	value="">
		<input type="hidden" name="fieldName" 	value="<?php echo $fieldName;?>">
		<input type="hidden" name="orderType" 	value="<?php echo $orderType;?>">
	</form>
<table><tr><td height="82px">&nbsp;</td></tr></table>
<?php }

function showData($id)
{ 
	$groups			= new groups();
	$departments 	= new departments();
	$course 		= new course();
	$users 			= new users();
	if($id) 
	{
	  $groups->showData($id);
	  
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

function sendEmail(str, sesid, grpid)
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
xmlhttp.open("GET","ajaxphp/sendemail.php?q="+str+"&s="+sesid+"&g="+grpid,true);
xmlhttp.send();
}


</script>
<?php 
		$dept_name 	 = $departments->getDepartmentName($groups->department_id);
		if($result[$i]['course_id']!=0) {
			$course_name = $course->getCourseName($groups->course_id);
		}
		$user_name 	 = $users->getName($groups->grp_created_by);
		
		
?>

  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
  <tr>
    <td align="center">
		<table width="100%" align="center" cellpadding="5" cellspacing="2">
          <tr class="TDHEAD"> 
            <td colspan="3" style="padding-left:10px;" class="text_main_header"> 
             View Group's Information</td>
          </tr>
          <tr> 
            <td width="26%" align="right" valign="top" class="tbllogin">Group Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td align="left" valign="top"><?php echo  stripslashes($groups->grp_name); ?>
            </td>
          </tr>
          <tr> 
          <td align="right" valign="top" class="tbllogin">Department </td>
          <td align="center" valign="top" class="tbllogin">:</td>
          <td  align="left" valign="top"><?php echo $dept_name;?></td>
          </tr>
          <tr> 
          <td align="right" valign="top" class="tbllogin">Course </td>
          <td align="center" valign="top" class="tbllogin">:</td>
          <td  align="left" valign="top">
		  <?php 
		  	if($result[$i]['course_id']!=0) {
				echo $course_name;
			}else {
				echo "----";
			}
				
			?></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin">Created By</td>
             <td align="center" valign="top" class="tbllogin">:</td>
            <td  align="left" valign="top"><?php echo $user_name;?></td>
          </tr>
		  
		  <tr> 
            <td class="tbllogin" align="right" valign="top">Is Active</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top">
                <?php echo $groups->is_active=='N' ? 'In Active' : 'Active';?>
            </td>
          </tr>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td class="point_txt"><input name="button" type="button" class="button" onClick="javascript:window.location='<?=$_SERVER['PHP_SELF']?>';"  value="Back"> &nbsp;&nbsp;
            <?php 
				/////////////////////////// check whether member already in group///////////////////////////////////
				$sql_chk = "select * from group_members where `user_id`='".$_SESSION['id']."' and `group_id`='".$groups->id."'";
				$row_chk = mysql_query($sql_chk);
				$res_chk = mysql_num_rows($row_chk);
				
				///////////////////////////////////////check whether group creater//////////////////////////////////
				
				$sql_gcchk = "select * from groups where `grp_created_by`='".$_SESSION['id']."' and id = '".$groups->id."'";
				$row_gcchk = mysql_query($sql_gcchk);
				$res_gcchk = mysql_num_rows($row_gcchk);
				
				//echo $res_chk."here=====".$res_gcchk; die;
				
				if($res_chk <= 0 && $res_gcchk <= 0) {
			?>
            		<a class="various1" href="#inline">Join This Group</a>
            <?php }?>
            <div style="display:none;">
            <div id="inline" style="width:550px;height:290px;overflow:auto;">
            	<div align="center" style="color:#006600; font-weight:bold;">Join This Group</div>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #999;">
                
                	
                    <tr>
                        <td style="background:#F0F0F0;" id="txtHint">Send request to Group Creater for accepting your "Join Group" request by clicking the join button below.</td>
                    </tr>
                     <tr>
                        <td style="background:#F0F0F0;" align="center">
                        	<input type="button" name="send_email" value="Send Email" class="button" onclick="sendEmail(<?php echo $groups->grp_created_by;?>, <?php echo $_SESSION['id'];?>, <?php echo $groups->id;?>)"/>
                        </td>
                    </tr>
                                               
                </table>
            </div>
        </div>
            </td>
          </tr>
        </table>
	</td>
  </tr>
</table>
<table><tr><td height="170px">&nbsp;</td></tr></table>
<? 
 } /////////////// End of function showData()

function saveData($id)
{ 
	$groups = new groups();
	$utility = new utility();
	if($id>0)
    {
		$groups->showData($id);
	}
			
?>
<script language="javascript">

function showCourse(str, crs)
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
xmlhttp.open("GET","ajaxphp/getcourse.php?q="+str+"&s="+crs,true);
xmlhttp.send();
}


</script>
<form name="frmUser" id="frmUser" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data"   >
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="id" value="<?php echo $id;?>" >
  <input type="hidden" name="grp_created_by" value="<?php echo $_SESSION['id'];?>" >
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
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Group Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="grp_name" id="grp_name" class="validate[required] inplogin" value="<?php echo stripslashes($groups->grp_name);?>"> 
            </td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Select Department</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top">
			<?php
						$query_dept = "select * from departments where `status`='Y'";
						$row_dept = mysql_query($query_dept);
				?>
				  <select name="department_id" onChange="showCourse(this.value, '<?php echo $groups->course_id?>')"> 
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
            <td width="71%" align="left" valign="top" id="txtHint">
           <?php $query_course = "select * from course where `course_id`='".$groups->course_id."'";
					$row_course = mysql_query($query_course);
			?>

			  <select name="course_id">
				<option value="">---Select----</option>	
					<?php while($res_course = mysql_fetch_array($row_course)) {?>
					<option value="<?php echo $res_course['course_id'];?>" <?php if($res_course['course_id']==$groups->course_id) { ?> selected="selected" <?php }?>><?php echo $res_course['course_name'];?>	</option>
					<?php }?>
					
  				</select>
            </td>
          </tr>
          
			            <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td> <input name="submit" type="submit" class="button" value="<?php echo $id >0 ?'Update':'Add'?>"> 
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