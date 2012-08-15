<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.getetutor.php");
include_once(PATH_TO_CLASS."class.menu.php");
include_once(PATH_TO_CLASS."class.utility.php");
require_once(INCLUDES_PATH."chksession.php");

$utility 	= new utility();
$menu 	= new menu();


if($_SESSION['username']=='') {
	header('Location: index.php');
	exit();
}

$mode	= $utility->cleanData($_POST['mode']);
$id 	= $utility->cleanData($_POST['id']);

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

} else if($mode =='change_status') 
{					  
  	$menu->activeDeactive($id);
  	$GLOBALS['admin_msg'] = $menu->errors;
	disphtml("main();");
} 
else if($mode=="save") 
{	
	
	//print_r($_POST); die;
	foreach($_POST as $key=>$val) 
	{
		if($key!='mode' && $key!='submit')	
		{
			$menu->$key=$val;
		}
	}

	if($id>0) 
	{
		if($menu->save($id)) 
		{
			$GLOBALS['admin_msg'] = "Menu updated successfully";
			disphtml("main();");
		} else {
			$GLOBALS['admin_msg'] = $menu->admin_msg;
		  	disphtml("saveData(".$id.");");
		}
	} 
	else 
	{
		if($menu->save($id="NULL")) 
		{
			$GLOBALS['admin_msg'] = "Menu inserted successfully";
			disphtml("main();");
		} 
		else 
		{
			$GLOBALS['admin_msg'] = $menu->errors;
			$id=-1;
		  	disphtml("saveData($id);");
		}
	}
} 
else if($mode=='delete' && isset($id)) 
{
	$menu->deleteData($id);
	$GLOBALS['admin_msg'] = "Menu deleted successfully";
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
	$menu		= new menu();
	//$area 		= new area();
	
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
		$orderBy='ORDER BY menu_id DESC';
	}
	
		$member_sql = " SELECT 	* 
						FROM 	".$menu->table_name."  ".$orderBy."  
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
						
						//echo $member_sql; die;
						
		$row=$menu->search(" SELECT COUNT(*) FROM ".$menu->table_name."  ");
		$count=$row[0][0];
	
	$result=$menu->search($member_sql);
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
    <td colspan="9">Menu Management</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="10%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="22%" ><strong>Menu Id</strong></td>
	<td width="30%" ><strong>Menu Name</strong></td>
    <td width="33%" ><strong>Menu Parent</strong></td>
    <td width="10%" ><div align="center"><strong>Is Active</strong></div></td>
    <td width="5%"  ><div align="center"><strong>View</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Edit</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Delete</strong></div></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) {
		$menu_arr = $menu->getMenuName($result[$i]['menu_id']);
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo $result[$i]['menu_id'];?></td>
    <td valign="top" align="left"><?php echo $result[$i]['menu_name'];?> </td>
    <td valign="top" align="left">
	<?php
	 //echo $result[$i]['parent_id']; die;
	$parentmenu_name = $menu->getParentMenuName($result[$i]['parent_id']);
	
	if($parentmenu_name == ""){
		echo "<font style=\"animation-iteration-count:infinite\" color=\"#000099\">No Parent/Main Menu</font>";
	}else {
		echo $parentmenu_name;
	}
	?> </td>
    <td valign="top" align="center"><a href="javascript:ChangeStatus(<?php echo $result[$i]['menu_id'];?>,<?php echo $GLOBALS['start']; ?>)" title="<?php echo ($result[$i]['is_active']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['is_active']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a></td>
    <td align="center" valign="top"><a href="javascript:viewData( <?php echo $result[$i]['menu_id'];?>);" title="View Menu Details"><img src="images/preview_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:editData( <?php echo $result[$i]['menu_id'];?>);" title="Edit Menu Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:deleteData( <?php echo $result[$i]['menu_id'];?>);" title="Delete Menu Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td>
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
	$menu	= new menu();
	if($id) 
	{
	  $menu->showData($id);
	  
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
             View Menu's Information</td>
          </tr>
          <tr> 
            <td width="26%" align="right" valign="top" class="tbllogin">Menu Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td align="left" valign="top"><?php echo  stripslashes($menu->menu_name); ?>
            </td>
          </tr>
          <tr> 
          <td align="right" valign="top" class="tbllogin">Menu Parent Name </td>
          <td align="center" valign="top" class="tbllogin">:</td>
          <td  align="left" valign="top"><?php echo stripslashes($menu->menu_name);?></td>
          </tr>
          		  
		  <tr> 
            <td class="tbllogin" align="right" valign="top">Is Active</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top">
                <?php echo $menu->is_active=='N' ? 'In Active' : 'Active';?>
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
	$menu = new menu();
	//$area 	= new area();
	$utility = new utility();
	if($id>0)
    {
		$menu->showData($id);
		$menu_value = $menu->menu_id;
		$parent_value = $menu->parent_id;
		
		if($parent_value==0) {
			
			 echo "<script language=\"javascript\">
			 	document.getElementById('div_main_menu').style.visibility = \"visible\";
				document.getElementById('div_sub_menu').style.visibility = \"hidden\";
			 </script>";
		}else if($parent_value > 0) {
			echo "<script language=\"javascript\">
			 	document.getElementById('div_main_menu').style.visibility = \"visible\";
				document.getElementById('div_sub_menu').style.visibility = \"hidden\";				
			 </script>";
		}
		
	}
	else
	{
		$menu_value = "";
		$parent_value = "";
	}
	
	//$menu_value = "";
?>
<script language="javascript">

/*$(document).ready(function() {
div_main_menu
});

*/



function view_main_menu_div()

{

	
	$("#div_main_menu").show();

	$("#div_sub_menu").hide();

}


function view_sub_menu_div()

{
	$("#div_main_menu").hide();

	$("#div_sub_menu").show();

}

</script>
 
 <?php if($id<0) {?>
  <table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">

  <?php if($GLOBALS['msg']!=""){?>

  <tr>

  	<td colspan="2" style="color:#FF0000"><strong><?php echo $GLOBALS['msg'];?></strong></td>

  </tr>

  <?php } 

	if($_SESSION['type']=="SUP")

	{

		$display ="none";

	?>

  <tr class="TDHEAD"> 

    <td colspan="2">Select your option first</td>

  </tr> 

    <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''">

        <td width="9%" align="center"><input type="radio" name="add_menu_option" id="main_menu" onclick="view_main_menu_div();" /></td>

        <td align="left">Add Main Menu</td>

    </tr>

    

    

    <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''">

        <td width="9%" align="center"><input type="radio" name="add_menu_option" id="sub_menu" onclick="view_sub_menu_div();"  /></td>

        <td align="left">Add Sub Menu</td>

    </tr>

    <?php
	}
	else
	{
		$display ="block";
	}

	?>



</table>
<?php }?>
<br />

<div id="div_main_menu" style="display:<?php echo $display;?>;">

  	<form name="frmMenu" id="frmMenu" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data"   >
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="id" value="<?php echo $id;?>" >
  <?php if($GLOBALS['msg']!='') { ?>
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?php echo $GLOBALS['msg'];?></td>
		</tr>
  </table>
  <?php } $GLOBALS['msg']=''; ?>
  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>&nbsp;</td></tr></table>

<!---->
 <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
<tr>
    <td align="center">
		<table width="100%" align="center" cellpadding="5" cellspacing="2">
          <tr class="TDHEAD"> 
            <td colspan="3" style="padding-left:10px;" class="text_main_header"> 
              <?php echo ucwords($_POST['mode']);?> Menu Information</td>
          </tr>
          <tr> 
            <td colspan="3" align="right"><b><font color="#FF0000">All * marked 
              fields are mandatory</font></b></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Menu Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="menu_name" id="menu_name" class="validate[required,custom[integer]] inplogin" value="<?php echo stripslashes($menu->menu_name);?>">
            </td>
          </tr>
          
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Visible to</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><select name="sub_admin">
            	<option value="0" <?php if($menu->sub_admin=='0') { ?> selected="selected" <?php }?>>Only Admin</option>
                <option value="0,1" <?php if($menu->sub_admin=='0,1') { ?> selected="selected" <?php }?>>Only Tutor</option>
                <option value="0,2" <?php if($menu->sub_admin=='0,2') { ?> selected="selected" <?php }?>>Only Student</option>
                <option value="0,1,2" <?php if($menu->sub_admin=='0,1,2') { ?> selected="selected" <?php }?>>Tutor & Student both</option>
            </select>
            </td>
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

</div>

<br />

<div id="div_sub_menu" style="display:none;">
	<form name="frmMenu" id="frmMenu" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data"   >
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="id" value="<?php echo $id;?>" >
  <?php if($GLOBALS['msg']!='') { ?>
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?php echo $GLOBALS['msg'];?></td>
		</tr>
  </table>
  <?php } $GLOBALS['msg']=''; ?>
  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>&nbsp;</td></tr></table>

<!---->
 <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
<tr>
    <td align="center">
		<table width="100%" align="center" cellpadding="5" cellspacing="2">
          <tr class="TDHEAD"> 
            <td colspan="3" style="padding-left:10px;" class="text_main_header"> 
              <?php echo ucwords($_POST['mode']);?> Menu Information</td>
          </tr>
          <tr> 
            <td colspan="3" align="right"><b><font color="#FF0000">All * marked 
              fields are mandatory</font></b></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Menu Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="menu_name" id="menu_name" class="validate[required,custom[integer]] inplogin" value="<?php echo stripslashes($menu->menu_name);?>">
            </td>
          </tr>
		  
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Visible to</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><select name="sub_admin">
            	<option value="0" <?php if($menu->sub_admin=='0') { ?> selected="selected" <?php }?>>Only Admin</option>
                <option value="0,1" <?php if($menu->sub_admin=='0,1') { ?> selected="selected" <?php }?>>Only Tutor</option>
                <option value="0,2" <?php if($menu->sub_admin=='0,2') { ?> selected="selected" <?php }?>>Only Student</option>
                <option value="0,1,2" <?php if($menu->sub_admin=='0,1,2') { ?> selected="selected" <?php }?>>Tutor & Student both</option>
            </select>
            </td>
          </tr>
           
          <tr> 
            <td class="tbllogin" align="right" valign="top"><font color="#FF0000">*</font>Parent</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top"><?php echo $menu->menuParentListOption($parent_value);?></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Page Name</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="page_name" id="page_name" class="validate[required,custom[integer]] inplogin" value="<?php echo stripslashes($menu->page_name);?>">
            </td>
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

</div>

<table><tr><td height="238px">&nbsp;</td></tr></table>

<? 
 } /////////////// End of function editData()
?>