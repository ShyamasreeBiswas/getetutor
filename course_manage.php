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

if($mode=='view') 
{
	//echo $id; die;
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

}else if($mode =='change_status') 
{					  
  	$course->activeDeactive($id);
  	$GLOBALS['admin_msg'] = $course->errors;
	disphtml("main();");
} 
else if($mode=="save") 
{	
	
	foreach($_POST as $key=>$val) 
	{
		if($key!='mode' && $key!='submit')	
		{
			$course->$key=$val;
		}
	}

	if($id>0) 
	{
		if($course->save($id)) 
		{
			$GLOBALS['admin_msg'] = "Course updated successfully";
			disphtml("main();");
		} else {
			$GLOBALS['admin_msg'] = $course->admin_msg;
		  	disphtml("saveData(".$id.");");
		}
	} 
	else 
	{
		if($course->save($id="NULL")) 
		{
			$GLOBALS['admin_msg'] = "Department inserted successfully";
			disphtml("main();");
		} 
		else 
		{
			$GLOBALS['admin_msg'] = $course->errors;
			$id=-1;
		  	disphtml("saveData($id);");
		}
	}
} 
else if($mode=='delete' && isset($id)) 
{
	$course->deleteData($id);
	$GLOBALS['admin_msg'] = "Course deleted successfully";
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
	$course		= new course();
	$departments 	= new departments();
	
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
		$orderBy='ORDER BY course_id DESC';
	}
	
		$member_sql = " SELECT 	* 
						FROM 	".$course->table_name."  ".$orderBy."  
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
						
						//echo $member_sql; die;
						
		$row=$course->search(" SELECT COUNT(*) FROM ".$course->table_name."  ");
		$count=$row[0][0];
	
	$result=$course->search($member_sql);
?>

<script language="JavaScript">
function show_all()
{
	document.frmSearch.search_mode.value = "";	
	document.frmSearch.txt_search.value  = "";
	document.frmSearch.search_type.value="";
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
		<td width="27%" align="center" class="ErrorText">&nbsp;</td>
		<td width="4%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(10,20,30,'frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
		<td width="5%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
        <td width="5%" align="right"><a href="javascript:addData();" title=" Add Record "><img border="0" src="images/plus_icon.gif"></a></td>
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Course Management</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="10%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="22%" align="center"><strong>Course Id</strong></td>
	<td width="30%" ><strong>Course Name</strong></td>
    <td width="33%" ><strong>Department Name</strong></td>
    <td width="10%" ><div align="center"><strong>Is Active</strong></div></td>
    <td width="5%"  ><div align="center"><strong>View</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Edit</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Delete</strong></div></td>
    
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) {
		$dept_name = $departments->getDepartmentName($result[$i]['department_id']);		
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="center"><?php echo $result[$i]['course_id'];?></td>
    <td valign="top" align="left"><?php echo $result[$i]['course_name'];?> </td>
    <td valign="top" align="left"><?php echo $dept_name;?> </td>
    <td valign="top" align="center"><a href="javascript:ChangeStatus(<?php echo $result[$i]['course_id'];?>,<?php echo $GLOBALS['start']; ?>)" title="<?php echo ($result[$i]['is_active']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['is_active']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a></td>
    <td align="center" valign="top"><a href="javascript:viewData( <?php echo $result[$i]['course_id'];?>);" title="View Department Details"><img src="images/preview_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:editData( <?php echo $result[$i]['course_id'];?>);" title="Edit Department Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:deleteData( <?php echo $result[$i]['course_id'];?>);" title="Delete Department Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td>
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
		<input type="hidden" name="search_type" value="<?php echo $search_type;?>">
		<input type="hidden" name="search_mode" value="<?php echo $search_mode;?>">
		<input type="hidden" name="txt_alpha" 	value="<?php echo $txt_alpha;?>">
		<input type="hidden" name="txt_search"  value="<?php echo $txt_search;?>">
		<input type="hidden" name="hold_page" 	value="">
		<input type="hidden" name="fieldName" 	value="<?php echo $fieldName;?>">
		<input type="hidden" name="orderType" 	value="<?php echo $orderType;?>">
	</form>
<table><tr><td height="82px">&nbsp;</td></tr></table>
<?php } ?>

<?php
function showData($id)
{ 
	$course	= new course();
	if($id) 
	{
	  $course->showData($id);
	  
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
             View Course's Information</td>
          </tr>
          
          <tr> 
          <td align="right" valign="top" class="tbllogin">Course Name </td>
          <td align="center" valign="top" class="tbllogin">:</td>
          <td  align="left" valign="top"><?php echo stripslashes($course->course_name);?></td>
          </tr>
          
          	<tr> 
            <td width="26%" align="right" valign="top" class="tbllogin">Department Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td align="left" valign="top"><?php echo  stripslashes($course->department_id); ?>
            </td>
          </tr>	  
		  <tr> 
            <td class="tbllogin" align="right" valign="top">Is Active</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top">
                <?php echo $course->is_active=='N' ? 'In Active' : 'Active';?>
            </td>
          </tr>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td class="point_txt"><input name="button" type="button" class="button" onClick="javascript:window.location='<?php echo $_SERVER['PHP_SELF'];?>';"  value="Back"> 
            </td>
          </tr>
        </table>
	</td>
  </tr>
</table>
<table><tr><td height="170px">&nbsp;</td></tr></table>
<?php 
 } /////////////// End of function showData()

function saveData($id)
{ 
	$course = new course();
	$departments = new departments();
	$utility = new utility();
	if($id>0)
    {
		$course->showData($id);
		$course_value = $course->course_id;
		$dept_value = $course->department_id;
	}
	else
	{
		$course_value = "";
		$dept_value = "";
	}
	
?>
<script language="javascript">
$(document).ready(function() {
	$("#frmCourse").validationEngine();
});

<!--code for email exist check start-->

$(document).ready(function(){
	$('#submit').attr('disabled','');
	var infook = false;
	var boxes = $(".inplogin");
	var myForm = $("#frmDepartment"), code = $("#code"), name = $("#name"), showInfo = $("#showInfo");
	
	
	
	//Form Validation
	myForm.submit(function(){
		
		if(!infook)
		{
			alert("Check For correct one");
			code.attr("value","");
			code.focus();
			return false;
		}
	});
	
	//send ajax request to check code
	code.blur(function(){
		$.ajax({
			type: "POST",
			data: "code="+$(this).attr("value"),
			url: "ajaxphp/checkdepartment.php",
			beforeSend: function(){
				showInfo.html("<font color='blue'>Checking ....</font>");
			},
			success: function(data){
				
				if(data != "0")
				{
					infook = false;
					showInfo.html("<font color='red'>Code Already Exist</font>");
				}
				else
				{
					infook = true;
					showInfo.html("<font color='green'>Code OK</font>");
				}
			}
		});
	});
	
	//send ajax request to check name
	name.blur(function(){
		$.ajax({
			type: "POST",
			data: "name="+$(this).attr("value"),
			url: "ajaxphp/checkdepartment.php",
			beforeSend: function(){
				showInfo.html("<font color='blue'>Checking ....</font>");
			},
			success: function(data){
				
				if(data != "0")
				{
					infook = false;
					showInfo.html("<font color='red'>Name Already Exist</font>");
				}
				else
				{
					infook = true;
					showInfo.html("<font color='green'>Name OK</font>");
				}
			}
		});
	});
});

<!--code for email exist check end-->

</script>
<form name="frmCourse" id="frmCourse" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data"   >
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="id" value="<?php echo $id;?>" >
  <? if($GLOBALS['msg']!='') { ?>
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?php echo $GLOBALS['msg'];?></td>
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
              <?php echo ucwords($_POST['mode']);?> Course Information</td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="top" class="tbllogin"> 
          	<div id="showInfo" align="right"></div></td>
            <td align="right"><b><font color="#FF0000">All * marked 
              fields are mandatory</font></b></td>
          </tr>
          
		   
          <tr> 
            <td class="tbllogin" align="right" valign="top"><font color="#FF0000">*</font>Course Name</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top"><input type="text" name="course_name" id="course_name" class="validate[required] inplogin" value="<?php echo stripslashes($course->course_name);?>"></td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Department Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><?php echo $departments->departmentListOption($dept_value);?></td>
          </tr>
          
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td> <input name="submit" type="submit" class="button" value="<?php echo $id >0 ?'Update':'Add'?>"> 
              &nbsp; <input name="button" type="button" class="button" onClick="javascript:window.location='<?php echo $_SERVER['PHP_SELF'];?>';"  value="Cancel">            </td>
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