<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.getetutor.php");
include_once(PATH_TO_CLASS."class.users.php");
include_once(PATH_TO_CLASS."class.schedules.php");
include_once(PATH_TO_CLASS."class.schedulesreq.php");
include_once(PATH_TO_CLASS."class.utility.php");
require_once(INCLUDES_PATH."chksession.php");

$utility 	= new utility();
$schedules 	= new schedules();


if($_SESSION['username']=='') {
	header('Location: index.php');
	exit();
}

$mode	= $utility->cleanData($_POST['mode']);
$id 	= $utility->cleanData($_POST['id']);
$schid 	= $utility->cleanData($_POST['schid']);
$studid 	= $utility->cleanData($_POST['studid']);
$user_id 	= $utility->cleanData($_POST['user_id']);

if($mode=='view') 
{
	disphtml("showData(".$id.");");
}
else if($mode =='uploadcsv') 
{					  
  	
	
	$file_arr = explode(".", $_FILES['csvfile']['name']);
		//echo $file_arr[1]; die;
		
	if($_FILES['csvfile']['name']=="") {
		$mssg ="Please Browse a CSV File";
	}else if($file_arr[1]!="csv") {
			$mssg ="Invalid file!! Please Browse a CSV File";
	}else {
			
		$output = array('Pass'  => 0, 'Fail' => 0);	
		ini_set('auto_detect_line_endings',1);	
		$handle = fopen($_FILES['csvfile']['tmp_name'], 'r');
		$counter = 0;
		$mssg_string = "";
		$arr_data = array();
		
		while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
		   
			//print_r($data);
			
			if($counter >0) {
			
					/*echo "<pre>";
					print_r($data);*/
					
					$arr_data[$counter] = $data;
			}
			$counter++;
		}
		
		/*echo "<pre>";
		print_r($arr_data);*/
		
		$flag_empty = 0;
		
		foreach($arr_data as $key=>$val){
			if(!empty($val[$key])){
				$flag_empty = 0;
			}else {
				$flag_empty = 1;
			}
		}
		
		if($flag_empty==0) {
			
			$flag = 0; 
			foreach($arr_data as $key=>$val){
			
				/*echo "<pre>";
		print_r($val);die;*/
				
					$val1 = mysql_real_escape_string($val[0]);
					if($val1=="") {
						$flag=1;
						$mssg_string.= "Empty Date in Row".$counter."<br>";
					}
					
					$val2 = mysql_real_escape_string($val[1]);
					if($val2=="") {
						$flag=1;
						$mssg_string.= "Empty Day in Row".$counter."<br>";
					}
					
					$val3 = mysql_real_escape_string($val[2]);
					if($val3=="") {
						$flag=1;
						$mssg_string.= "Empty Time in Row".$counter."<br>";
					}		
					
					$val3 = mysql_real_escape_string($val[2]);					
					if($val3!="") {
						$val3_arr = explode(":", $val3);						
						if($val3_arr[0]=="" || $val3_arr[1]=="") {
							$flag=1;
							$mssg_string.= "Time Wrong in Row".$counter."<br>";
						}
					}   
					
					$val4 = mysql_real_escape_string($val[3]);
					if($val4=="") {
						$flag=1;
						$mssg_string.= "Empty Max Strength in Row".$counter."<br>";
					}		
										
			}
			
			if($flag==0) {
			
				foreach($arr_data as $keyin=>$valin){
					
					$val1 = mysql_real_escape_string($valin[0]);
					$val2 = mysql_real_escape_string($valin[1]);
					$val3 = mysql_real_escape_string($valin[2]);
					$val4 = mysql_real_escape_string($valin[3]);
					
					mysql_query("INSERT INTO `schedules` (`sdate`, `day`, `stime`, `maxstrength`, `user_id`) VALUES ('{$val1}', '{$val2}', '{$val3}', '{$val4}', '".$_SESSION['id']."')");
				}
				$mssg = "File Successfully uploaded";
			}else if($flag==1) {
				$mssg = "File Upload Failed<br>".$mssg_string;
			}
		}else if($flag_empty==1) {
			$mssg = "File Upload Failed";
		}
			
	}
	
	//////////////////////////////////////////
	$GLOBALS['admin_msg'] = $mssg;
	disphtml("main();");
} 
 else if($mode =='change_status') 
{					  
  	//echo $id; die;
	$schedules->activeDeactive($id);
  	$GLOBALS['admin_msg'] = $schedules->errors;
	disphtml("main();");
} 
else if($mode=="approve") 
{
	//echo $schid; die;
	$sql_app = "update schedule_request set approve = 'Y' where schedule_id = '".$schid."' and student_id = '".$studid."' and tutor_id = '".$_SESSION['id']."'"; 
	mysql_query($sql_app);
	disphtml("showData(".$schid.");");
} 
else if($mode=='delete') 
{
	$sql_del = "delete from schedule_request where schedule_id = '".$schid."' and student_id = '".$studid."' and tutor_id = '".$_SESSION['id']."'";
	mysql_query($sql_del);
	$GLOBALS['admin_msg'] = "Request deleted successfully";
	disphtml("showData(".$schid.");");
} 
else 
{
	disphtml("main();");
}
	
ob_end_flush();

function main() 
{
	
	$utility 		= new utility();
	$schedules 	= new schedules();
	
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
	
	if($user_type=='SUP') {
		$schedule_sql = " SELECT 	* 
						FROM 	".$schedules->table_name."  ".$orderBy."  
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
						
						//echo $member_sql; die;
						
		$row=$schedules->search(" SELECT COUNT(*) FROM ".$schedules->table_name."  ");
	}else {
			
			$schedule_sql = " SELECT 	* 
						FROM 	".$schedules->table_name."  WHERE user_id = '".$_SESSION['id']."' ".$orderBy."  
						LIMIT 	".$GLOBALS['start'].",".$GLOBALS['show'];
					
			$row=$schedules->search(" SELECT COUNT(*) FROM ".$schedules->table_name."  ");
		
	}
		//$count=$row[0][0];
	
		$result=$schedules->search($schedule_sql);
?>
<script language="javascript">


function view_upcsv_div()

{

	
	$("#div_upcsv").show();

	$("#div_manual").hide();

}


function view_manual_div()

{
	$("#div_upcsv").hide();

	$("#div_manual").show();

}

</script>

<style type="text/css">
@import "css/jquery.datepick.css";
</style>
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<script type="text/javascript">
$(function() {
	$('#sdate').datepick();
});
</script>
<script language="javascript">
$(document).ready(function() {
	$("#frmCsvmanual").validationEngine();
});
</script>


  <table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <?php if($GLOBALS['msg']!=""){?>
  <tr>
  	<td colspan="2" style="color:#FF0000"><strong><?php echo $GLOBALS['msg'];?></strong></td>
  </tr>
  <?php } ?>

  <tr class="TDHEAD"> 
    <td colspan="2">Select your option first</td>
  </tr> 

    <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''">
        <td width="9%" align="center"><input type="radio" name="add_schedule" id="upcsv" onclick="view_upcsv_div();" /></td>
        <td align="left">Upload Schedule Document</td>
    </tr>     

    <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''">
        <td width="9%" align="center"><input type="radio" name="add_schedule" id="manual" onclick="view_manual_div();"  /></td>
        <td align="left">Add Schedule Document Manually</td>

    </tr>

</table>

<br />

<div id="div_upcsv" style="display:none;">

  <form name="frmCsv" id="frmCsv" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
	<input type="hidden" name="mode" id="mode" value="uploadcsv" />	
	<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="3">Upload Schedule Doc</td>
    </tr>
	<tr class="content">
      <td width="30%" class="text_normal" align="right">Upload:</td>      
	  <td width="27%">
	  	<input type="file" name="csvfile" id="csvfile" class="inplogin"/></td>     
      
        <td width="43%" align="left"><input type="submit" class="button" value="Upload CSV"></td>
  </tr>
	
</table>
</form>

</div>

<br />

<div id="div_manual" style="display:none;">
	<form name="frmCsvmanual" id="frmCsvmanual" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
	<input type="hidden" name="mode" id="mode" value="save" />
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'];?>" >	
	<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
<tr>
    <td align="center">
		<table width="100%" align="center" cellpadding="5" cellspacing="2">
          <tr class="TDHEAD"> 
            <td colspan="3" style="padding-left:10px;" class="text_main_header"> 
              Add Schedule Manually</td>
          </tr>
          <tr> 
            <td colspan="3" align="right"><b><font color="#FF0000">All * marked 
              fields are mandatory</font></b></td>
          </tr>
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Date</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top"><input type="text" name="sdate" id="sdate" readonly="readonly" class="validate[required]" value="">
            </td>
          </tr>
		  
          <tr> 
            <td align="right" valign="top" class="tbllogin"><font color="#FF0000">*</font>Select Day</td>
            <td width="3%" align="center" valign="top" class="tbllogin">:</td>
            <td width="71%" align="left" valign="top">
            <select name="day">
            	<option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
            </td>
          </tr>
           
          <tr> 
            <td class="tbllogin" align="right" valign="top"><font color="#FF0000">*</font>Enter Time</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top"><input type="text" name="stime" id="stime" class="validate[required]" value=""></td>
          </tr>
          
          <tr> 
            <td class="tbllogin" align="right" valign="top"><font color="#FF0000">*</font>Enter Maximum Strength</td>
            <td align="center" valign="top" class="tbllogin">:</td>
            <td valign="top"><input type="text" name="maxstrength" id="maxstrength" class="validate[required]" value="">(Maximum strength for this session)</td>
          </tr>
          
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td> <input name="submit" type="submit" class="button" value="Add"> 
              &nbsp; <input name="button" type="button" class="button" onClick="javascript:window.location='<?php echo $_SERVER['PHP_SELF'];?>';"  value="Cancel">            </td>
          </tr>
        </table>
	</td>
  </tr>
  </table>
</form>
</div>




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
	//alert(ID);
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

/*function deleteData(id)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true ) {
		document.frm_opts.mode.value='delete';
		document.frm_opts.id.value=id;
		document.frm_opts.submit();
	}
}*/
</script>
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="47%" align="center" class="ErrorText"><!--No. of records to be shown:--></td>
		<td width="10%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(100,500,1000,'All','frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Schedule Chart</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="8%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="18%" ><strong>Date</strong></td>
	<td width="22%" ><strong>Day</strong></td>
    <td width="17%" ><strong>Time</strong></td>
    <td width="10%" ><div align="center"><strong>Is Active</strong></div></td>
    <td width="15%"  ><div align="center"><strong>View Requests</strong></div></td>
    <!--<td width="4%"  ><div align="center"><strong>Edit</strong></div></td>-->
    <!--<td width="6%"  ><div align="center"><strong>Delete</strong></div></td>-->
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) {
		
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo $result[$i]['sdate'];?></td>
    <td valign="top" align="left"><?php echo $result[$i]['day'];?> </td>
    <td valign="top" align="left"><?php echo $result[$i]['stime'];?> </td>
    <td valign="top" align="center"><a href="javascript:ChangeStatus(<?php echo $result[$i]['id'];?>,<?php echo $GLOBALS['start']; ?>)" title="<?php echo ($result[$i]['is_active']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['is_active']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a></td>
    <td align="center" valign="top"><a href="javascript:viewData( <?php echo $result[$i]['id'];?>);" title="View Requests"><img src="images/preview_icon.gif" border="0"></a></td>
    <!--<td align="center" valign="top"><a href="javascript:editData( <?php //echo $result[$i]['id'];?>);" title="Edit Schedule"><img src="images/edit_icon.gif" border="0"></a></td>-->
    <!--<td align="center" valign="top"><a href="javascript:deleteData( <?php //echo $result[$i]['id'];?>);" title="Delete Schedule"><img name="xx" src="images/delete_icon.gif" border="0"></a></td>-->
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
    
    <!--------------------------------------------------------------------------------------------------------->
    


<?php 
 } /////////////// start of function showData()

function showData($id)
{
	$schedulesreq = new schedulesreq();
	$users 		= new users();
	
	//echo $_SESSION['type']; die;
	if($_SESSION['type']== 'SUP') {
		
		$sql_req = "select * from schedule_request where schedule_id = '".$id."'";
	}else {
		$sql_req = "select * from schedule_request where schedule_id = '".$id."' and tutor_id = '".$_SESSION['id']."' ";
	}
	$result_req=$schedulesreq->search($sql_req);
	
?>
<script language="JavaScript">
function approve(schid, studid)
{
    //alert(schid);
	document.frm_req.mode.value='approve';
	document.frm_req.schid.value=schid;
	document.frm_req.studid.value=studid;
	document.frm_req.submit();
}

function deleteRequest(schid, studid)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true ) {
		document.frm_req.mode.value='delete';
		document.frm_req.schid.value=schid;
		document.frm_req.studid.value=studid;
		document.frm_req.submit();
	}
}
</script>
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr> 
		<td width="47%" align="center" class="ErrorText">&nbsp;</td>
		<td width="10%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(10,20,30,'frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
	</tr>
</table>
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Manage Requests</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="15%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="25%" ><strong>Student Name</strong></td>
	<td width="29%" ><strong>Student Email</strong></td>
    <td width="20%"  ><div align="center"><strong>Approve</strong></div></td>
    <td width="11%"  ><div align="center"><strong>Delete</strong></div></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result_req[$i]!=NULL) {
		
		$student_name = $users->getName($result_req[$i]['student_id']);
		$student_email = $users->getEmail($result_req[$i]['student_id']);
		
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo $student_name;?></td>
    <td valign="top" align="left"><a href="mailto:<?php echo $student_email;?>"><?php echo $student_email;?></a> </td>
    <td align="center" valign="top">
    <?php if($result_req[$i]['approve'] == 'N') {?>
    	<a href="javascript:approve( <?php echo $result_req[$i]['schedule_id'];?>, <?php echo $result_req[$i]['student_id'];?>);" title="Edit Schedule">Approve</a>
       <?php }else {?>
       <font color="#006600">Approved</font>
       <?php }?>
       </td>
    <td valign="top" align="center"><a href="javascript:deleteRequest( <?php echo $result_req[$i]['schedule_id'];?>, <?php echo $result_req[$i]['student_id'];?>);" title="Delete Schedule"><img name="xx" src="images/delete_icon.gif" border="0"></a></td>
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
	
	<form name="frm_req" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" >
		<input type="hidden" name="mode" value="">
        <input type="hidden" name="id">
		<input type="hidden" name="schid">
        <input type="hidden" name="studid">
	</form>

<?php }?>