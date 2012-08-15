<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.users.php");
require_once(PATH_TO_CLASS.'class.log.php');
require_once(PATH_TO_CLASS.'class.schedules.php');
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
$id 		= $utility->cleanData($_POST['id']);

$search_mode	= $utility->cleanData($_POST['search_mode']);

if($search_mode=="SEARCH")
{
	
	disphtml("main();");	

}else if($mode=='getapp') {
	
	disphtml("showData(".$user_id.");");
	
}else if($mode=='sendreq') {
	
	$qry_chkin = "select * from schedule_request where schedule_id = '".$id."' and student_id = '".$_SESSION['id']."' and tutor_id = '".$user_id."'";
	$row_chkin = mysql_query($qry_chkin);
	$res_chkin = mysql_num_rows($row_chkin);
	
	////////////////////////////getstrength/////////////////////////
	$sqlstrn = "select * from schedules where id = '".$id."'";
	$rowstrn = mysql_query($sqlstrn);
	$resstrn = mysql_num_rows($rowstrn);
	$resstatus = mysql_fetch_array($rowstrn);
	
	//echo $resstatus['status']; die;
	/////////////////////////////////////////////////////////////////
	
	if($res_chkin > 0) {
		$GLOBALS['admin_msg'] = "Already Request Sent";
	}else if($res_chkin ==  $resstrn){
		
		///////////////////// updating the status//////////////////////////
		$sqlupsts = "update schedules set `status` = 'F' where `id` = '".$id."'";
		mysql_query($sqlupsts);
		//////////////////////////////////////////////////////////////
		$qry_schin = "insert into schedule_request (`schedule_id`, `student_id`, `tutor_id`) values ('".$id."', '".$_SESSION['id']."', '".$user_id."') ";
		mysql_query($qry_schin);
		$GLOBALS['admin_msg'] = "Request Send successfully";
	}else if($res_chkin < $resstrn && $resstatus['status'] == 'V') {
	
		$qry_schin = "insert into schedule_request (`schedule_id`, `student_id`, `tutor_id`) values ('".$id."', '".$_SESSION['id']."', '".$user_id."') ";
		mysql_query($qry_schin);
		$GLOBALS['admin_msg'] = "Request Send successfully";
	}
	disphtml("showData(".$user_id.");");
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
	$log_details	= new log_details();
	
	$user_type = $_SESSION['type'];
	
	$hold_page 		= $utility->cleanData($_POST['hold_page']);
	$orderType 		= $utility->cleanData($_POST['orderType']);
	$fieldName		= $utility->cleanData($_POST['fieldName']);
	$search_mode	= $utility->cleanData($_POST['search_mode']);
	$department_id 	= $utility->cleanData($_POST['department_id']);
	$course_id		= $utility->cleanData($_POST['course_id']);
	$search_mode	= $utility->cleanData($_POST['search_mode']);
	
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
	
	if ($search_mode=="SEARCH") { 
	
				
		
			$member_sql = "select * from ".$users->table_name." where type='".SUB."' and department_id='".$department_id."' and course_id='".$course_id."'";
			$member_row = "select count(*) from ".$users->table_name." where type='".SUB."' and department_id='".$department_id."' and course_id='".$course_id."'";
		
		
		$member_sql .= $orderBy."  LIMIT ".$GLOBALS['start'].",".$GLOBALS['show'];
		//echo $member_sql; die;
		$row		 = $users->search($member_row);
		$count		 = $row[0][0];
		
		$result=$users->search($member_sql);
	}
	
?>
<style type="text/css">
@import "css/jquery.datepick.css";
</style>
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<script type="text/javascript">
$(function() {
	$('#from_date').datepick();
	$('#to_date').datepick();
});
</script>

<script language="javascript">
$(document).ready(function() {
	$("#frmSearch").validationEngine();
});
</script>
<?php if($_SESSION['err_msg']!='') { ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="center" style="color:#FF0000"><?php echo $_SESSION['err_msg']; $_SESSION['err_msg']='';?></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>
<?php } 

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
<form name="frmSearch" id="frmSearch" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="search_mode" id="search_mode" value="SEARCH" />	
	<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="7">Find Tutor</td>
    </tr>
	<tr class="content">
      <td width="12%" class="text_normal" align="right">Department</td>
      <td width="2%">:</td>
	  <td width="12%">
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
      <td width="5%" class="text_normal" align="right">Course</td>
      <td width="2%">:</td>
	  <td width="12%" id="txtHint">
	  	  <select name="course_id">
				<option value="">---Select----</option>					
  			</select>
      </td>
      
        <td width="11%" align="left"><input type="submit" class="button" value="Search"></td>
  </tr>
	
</table>
</form>

<script language="JavaScript">

function OrderBy(order_type,field_name)
{
	document.frm_opts.fieldName.value=field_name;
	document.frm_opts.orderType.value=order_type;
	document.frm_opts.submit();
}

/*function exportToCSV()
{
	document.getElementById("download_log").submit();
}*/

function apointmentData(user_id)
{
   
	document.frm_opts.mode.value='getapp';
	document.frm_opts.user_id.value=user_id;
	document.frm_opts.submit();
}

</script>
<?php if ($search_mode=="SEARCH") { ?>


<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr> 
		<td width="20%" align="center" class="ErrorText">Total records:<? echo $count;?></td>
		<td width="27%" align="center" class="ErrorText"><!--No. of records to be shown:--></td>
		<td width="4%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(100,500,1000,'All','frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
		<td width="5%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
        <!--<td width="5%" align="right"><a href="javascript:exportToCSV();" title=" download report  "><img border="0" src="images/csv.gif" height="15"></a></td>-->
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Tutor List</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="30%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="35%" ><strong>Name</strong></td>
    <td width="30%" ><strong>View Schedule & Get Appointment</strong></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) 
	{
		
	?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo   $result[$i]['name'];?></td>
    <td valign="top" align="left"> <a href="javascript:apointmentData( <?php echo $result[$i]['id'];?>);" title="Get Appointment">Get Appointment</a></td>
    
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
		
	</form>
<?php } ?>
<table><tr><td height="82px">&nbsp;</td></tr></table>
<?php } 

function showData($user_id) {


	$schedules 	= new schedules();
	$users 		= new users();
	
	$user_name =  $users->getName($user_id);
	
	$query_sch = "SELECT * FROM schedules WHERE user_id = '".$user_id."'";
	$res_arr = $schedules->search($query_sch);	
	
			
?>
<script language="JavaScript">


function sendRequest(id, user_id)
{
   
	document.frm_sch.mode.value='sendreq';
	document.frm_sch.id.value=id;
	document.frm_sch.user_id.value=user_id;
	document.frm_sch.submit();
}

</script>
<? if($GLOBALS['admin_msg']!='') { ?>
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?=$GLOBALS['msg']?></td>
		</tr>
  </table>
  <? } $GLOBALS['admin_msg']=''; ?>
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="6"><?php echo $user_name;?>'s Schedule Chart</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="3%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="29%" ><strong>Date</strong></td>
    <td width="25%" ><strong>Day</strong></td>
    <td width="13%" ><strong>Time</strong></td>
    <td width="10%" ><strong>Status</strong></td>
    <td width="20%" >&nbsp;</td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($res_arr[$i]!=NULL) 
	{
		$status =0;
		$qry_chkinreq = "select * from schedule_request where schedule_id = '".$res_arr[$i]['id']."' and tutor_id = '".$res_arr[$i]['user_id']."'";
		$row_chkinreq = mysql_query($qry_chkinreq);
		$res_chkinreq = mysql_num_rows($row_chkinreq);
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$qry_cur_chkinreq = "select * from schedule_request where schedule_id = '".$res_arr[$i]['id']."' and student_id = '".$_SESSION['id']."' and tutor_id = '".$res_arr[$i]['user_id']."'";
		$row_cur_chkinreq = mysql_query($qry_cur_chkinreq);
		$res_cur_chkinreq = mysql_num_rows($row_cur_chkinreq);
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if($res_chkinreq < 3) {
		 	$status = "Vaccant";
		}else {
			$status = "<font color=\"#FF0000\">Full</font>";
		}
	?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo   $res_arr[$i]['sdate'];?></td>
    <td valign="top" align="left"><?php echo   $res_arr[$i]['day'];?></td>
    <td valign="top" align="left"><?php echo   $res_arr[$i]['stime'];?></td>
    <td valign="top" align="left"><?php echo   $status;?></td>
    <td valign="top" align="left">
    <?php 
		//echo $res_cur_chkinreq; 		
		if($res_chkinreq < 3 && $res_cur_chkinreq <= 0) {
		?>
        <a href="javascript:sendRequest( <?php echo $res_arr[$i]['id'];?>, <?php echo $res_arr[$i]['user_id'];?>);" title="Get Appointment">Request For Appoinment</a>
        <?php
		}else if($res_cur_chkinreq > 0){
			echo "N/A";
		}else {
			echo "N/A";
		}		
		
	?>
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
<form name="frm_sch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" >
		<input type="hidden" name="mode" value="">
		<input type="hidden" name="user_id">
		<input type="hidden" name="id">
	</form>
<? 
}

?>

