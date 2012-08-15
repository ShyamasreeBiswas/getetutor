<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.users.php");
require_once(PATH_TO_CLASS.'class.groupmembers.php');
include_once(PATH_TO_CLASS."class.utility.php");
require_once(INCLUDES_PATH."chksession.php");

$utility 	= new utility();
$users 		= new users();
$groupmembers = new groupmembers();


if($_SESSION['username']=='') {
	header('Location: index.php');
	exit();
}


$mode 		= $utility->cleanData($_POST['mode']);
$id 		= $utility->cleanData($_POST['id']);
$search_mode	= $utility->cleanData($_POST['search_mode']);

if($search_mode=="SEARCH")
{
	//echo $_POST['group_id'];die;
	disphtml("main();");	

}else if($mode=='acceptmem') {
	
	$sql_req = "update ".$groupmembers->table_name." set request_status = '0' where id = '".$id."'"; 
	mysql_query($sql_req);
	
	disphtml("main();");
	
}else if($mode =='change_status') 
{					  
  
	$groupmembers->activeDeactive($id);
  	$GLOBALS['admin_msg'] = $groupmembers->errors;
	disphtml("main();");
} 
else
{
	disphtml("main();");
}

ob_end_flush();

function main() 
{
	
?>

<?php if($_SESSION['err_msg']!='') { ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="center" style="color:#FF0000"><?php echo $_SESSION['err_msg']; $_SESSION['err_msg']='';?></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>
<?php } 

?> 

<form name="frmSearch" id="frmSearch" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="search_mode" id="search_mode" value="SEARCH" />	
	<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="4">Find Group to get members</td>
    </tr>
	<tr class="content">
      <td width="18%" class="text_normal" align="right">&nbsp;</td>
      <td width="13%" align="right"><span class="text_normal">Select Group</span>:</td>
	  <td width="22%" align="left">
	  	<?php
				if($_SESSION['type']=='SUP') {
					$query_grp=" select * from groups where is_active='Y'";
				}else {
					$query_grp=" select * from groups where grp_created_by = '".$_SESSION['id']."' and is_active='Y'";
				}
				$row_grp = mysql_query($query_grp);
		?>
		  <select name="group_id"> 
				<?php while($res_grp = mysql_fetch_array($row_grp)) {?>
				<option value="<?php echo $res_grp['id'];?>" <?php if($res_grp['id']==$_POST['group_id']) { ?> selected="selected" <?php }?>><?php echo $res_grp['grp_name'];?></option>
				<?php }?>
		  </select>
      </td>
          
        <td width="47%" align="left"><input type="submit" class="button" value="Search"></td>
  </tr>
	
</table>
</form>


<?php
	
		
	$utility 		= new utility();
	$users 			= new users();
	$groupmembers	= new groupmembers();
	
	$user_type = $_SESSION['type'];
	
	$hold_page 		= $utility->cleanData($_POST['hold_page']);
	$orderType 		= $utility->cleanData($_POST['orderType']);
	$fieldName		= $utility->cleanData($_POST['fieldName']);
	$search_mode	= $utility->cleanData($_POST['search_mode']);
	$repost			= $utility->cleanData($_POST['repost']);
	$grpid 			= $utility->cleanData($_POST['group_id']);
	
	if($hold_page>0) {
		$GLOBALS['start'] = $hold_page;
	}
	
	if($mode == "refresh") {
		$member_row = $_POST;
	}
	
	if($orderType && $fieldName) {
		$orderType 	= $orderType=='ASC'?' ASC ':' DESC ';
		$orderBy	= ' ORDER BY '.$fieldName.$orderType;
	} else {
		$orderBy=' ORDER BY id DESC';
	}
	
	//echo $search_mode; die;
	if ($search_mode=="SEARCH") { 			
		
		$member_sql = "select * from ".$groupmembers->table_name." where group_id='".$grpid."'";
		$member_row = "select count(*) from ".$groupmembers->table_name." where group_id='".$grpid."'";
			
			
		$member_sql .= $orderBy."  LIMIT ".$GLOBALS['start'].",".$GLOBALS['show'];
		//echo $member_sql; die;
		$row		 = $groupmembers->search($member_row);
		$count		 = $row[0][0];
		
		$result=$groupmembers->search($member_sql);
	}
	
	if($repost == "STATUS"){
		$member_sql = "select * from ".$groupmembers->table_name." where group_id='".$grpid."'";
		$member_row = "select count(*) from ".$groupmembers->table_name." where group_id='".$grpid."'";
			
			
		$member_sql .= $orderBy."  LIMIT ".$GLOBALS['start'].",".$GLOBALS['show'];
		//echo $member_sql; die;
		$row		 = $groupmembers->search($member_row);
		$count		 = $row[0][0];
		
		$result=$groupmembers->search($member_sql);
	}
	
?>
<script language="JavaScript">

function acceptMember(id)
{
   
	document.frm_opts.mode.value='acceptmem';
	document.frm_opts.id.value=id;
	document.frm_opts.submit();
}

function ChangeStatus(ID, grpid)
{
	document.frm_opts.mode.value='change_status';
	document.frm_opts.id.value=ID;
	document.frm_opts.group_id.value=grpid;
	document.frm_opts.repost.value='STATUS';
	document.frm_opts.submit();
}

</script>
<?php if ($search_mode=="SEARCH" || $repost == "STATUS") { ?>


<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr> 
		<td width="20%" align="center" class="ErrorText">Total records:<? echo $count;?></td>
		<td width="27%" align="center" class="ErrorText"><!--No. of records to be shown:--></td>
		<td width="4%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(100,500,1000,'All','frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
		<td width="5%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
       
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Group Members List</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="3%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="35%" ><strong>Member Name</strong></td>
    <td width="30%" ><strong>Member Email</strong></td>
    <td width="16%" ><strong>Accept Request</strong></td>
     <td width="10%" ><div align="center"><strong>Is Active</strong></div></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) 
	{
		$membername = $users->getName($result[$i]['user_id']);
		$memberemail = $users->getEmail($result[$i]['user_id']);
	?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo $membername;?></td>
    <td valign="top" align="left"><?php echo $memberemail;?></td>
    <td valign="top" align="left"> 
    <?php if($result[$i]['request_status']=='1') {?>
    <a href="javascript:acceptMember( <?php echo $result[$i]['id'];?>);" title="Accept Member">Accept</a>
    <?php }else {?>
    <font color="#006600">Accepted</font>
    <?php }?>
    </td>
    <td valign="top" align="center"><a href="javascript:ChangeStatus(<?php echo $result[$i]['id'];?>,<?php echo $result[$i]['group_id']; ?>)" title="<?php echo ($result[$i]['is_active']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['is_active']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a></td>
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
        <input type="hidden" name="group_id">
        <input type="hidden" name="repost"/>
	</form>
<?php } 

 } 

?>



