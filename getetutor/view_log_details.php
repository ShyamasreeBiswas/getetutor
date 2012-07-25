<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.users.php");
require_once(PATH_TO_CLASS.'class.log.php');	
require_once(PATH_TO_CLASS.'class.ip_country.php');	
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

$search_mode	= $utility->cleanData($_POST['search_mode']);

if($search_mode=="SEARCH")
{
	$from_date	= date("Y-m-d", strtotime($_POST['from_date']));
	$to_date 	= date("Y-m-d", strtotime($_POST['to_date']));
	
	$date_diff_sql 	= "SELECT datediff('".$to_date."', '".$from_date."') as datediff";
	$rs_date_diff	= $log_details->search($date_diff_sql);
	
	$actual_date_diff = $rs_date_diff[0]['datediff'];
	$user_id 	= $_POST['user_list'];
	
	if((($from_date=='1970-01-01') || ($from_date=='')) && (($to_date=='1970-01-01') || ($to_date=='')) && $user_id=='')
	{
		$_SESSION['err_msg'] = 'From Date and To Date can not be blank';
		header("location:view_log_details.php");
		exit();
	}

	else if($actual_date_diff < 0 )
	{
		$_SESSION['err_msg'] = 'From Date can not greater than To Date';
		header("location:view_log_details.php");
		exit();
	}
	
	else
	{
			disphtml("main();");
	}

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
	$ip_country 	= new ip_country();
	
	$user_type = $_SESSION['type'];
	
	$hold_page 		= $utility->cleanData($_POST['hold_page']);
	$orderType 		= $utility->cleanData($_POST['orderType']);
	$fieldName		= $utility->cleanData($_POST['fieldName']);
	$search_mode	= $utility->cleanData($_POST['search_mode']);
	$search_type 	= $utility->cleanData($_POST['search_type']);
	$txt_search		= $utility->cleanData($_POST['txt_search']);
	$txt_alpha		= $utility->cleanData($_POST['txt_alpha']);
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
	
		$from_date	= date("Y-m-d", strtotime($_POST['from_date']));
		$to_date 	= date("Y-m-d", strtotime($_POST['to_date']));
		
		$user_id 	= $_POST['user_list'];
		
		$where = " WHERE user_id !=1";
		$where .= " AND date(db_add_date) between '".$from_date."' AND '".$to_date."'";
		
		if($user_id>0)
		{
			$where .= " AND user_id = ".$user_id;
		}

		$member_sql = "SELECT * FROM ".$log_details->table_name.$where.$orderBy;
		$member_row = "SELECT COUNT(*) FROM ".$log_details->table_name.$where.$orderBy;
		 
		
		$member_sql .= "  LIMIT ".$GLOBALS['start'].",".$GLOBALS['show'];
		$row		 = $users->search($member_row);
		$count		 = $row[0][0];
		
		$result=$log_details->search($member_sql);
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
if($_POST['from_date']!="")
{
	$from_date = $_POST['from_date'];
}
else
{
	$from_date = date("m/d/Y");
}

if($_POST['to_date']!="")
{
	$to_date = $_POST['to_date'];
}
else
{
	$to_date = date("m/d/Y");
}
?> 
<form name="frmSearch" id="frmSearch" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="search_mode" id="search_mode" value="SEARCH" />	
	<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="10">Log Search Panel</td>
    </tr>
	<tr class="content">
      <td width="9%" class="text_normal">User Name</td>
      <td width="2%">:</td>
	  <td width="18%"><?php echo $users->userListOption($user_id);?>
      </td>
      <td width="13%">From Date</td>
      <td width="1%">:</td>
      <td width="17%"><input type="text" id="from_date" name="from_date" readonly="readonly" value="<?php echo $from_date;?>" class="validate[required]"></td>
      <td width="11%">To Date</td>
      <td width="3%">:</td>
      <td width="15%"><input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="validate[required]"></td>
	  <td width="11%"><input type="submit" class="button" value="Search"></td>
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

function exportToCSV()
{
	document.getElementById("download_log").submit();
}
</script>
<?php if ($search_mode=="SEARCH") { ?>

<form name="download_log" id="download_log" action="download_log.php" method="post">
    <input type="hidden" name="user_list" 	value="<?=$_POST['user_list'];?>">
    <input type="hidden" name="pageNo" 		value="<?=$_POST['pageNo']?>">
	<input type="hidden" name="from_date" 	value="<?php echo $from_date;?>">
	<input type="hidden" name="to_date" 	value="<?php echo $to_date;?>">

</form>
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="1">
	<tr> 
		<td width="20%" align="center" class="ErrorText">Total records:<? echo $count;?></td>
		<td width="27%" align="center" class="ErrorText"><!--No. of records to be shown:--></td>
		<td width="4%" align="center" class="ErrorText"><? //echo $utility->ComboResultPerPage(100,500,1000,'All','frm_opts');?></td>
		<td width="41%" align="center" class="ErrorText"><?php echo $GLOBALS['admin_msg'];?></td>
		<td width="5%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
        <td width="5%" align="right"><a href="javascript:exportToCSV();" title=" download report  "><img border="0" src="images/csv.gif" height="15"></a></td>
	</tr>
</table>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Log View List</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="3%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="16%" ><div align="left"><a href="javascript:OrderBy('<?php echo $orderType=='ASC'?'DESC':'ASC'; ?>','name')" title="Sort By Name"> <strong>Name</strong></a><?php if($fieldName=='name'){?><img src="images/<?php echo $orderType=='ASC'?'arrowup.gif':'arrowdn.gif'; ?>"><?php }?></div></td>
	<td width="35%" ><strong>Event Description</strong></td>
    <td width="30%" ><strong>IP Address (Country)</strong></td>
    <td width="16%" ><strong>Date &amp; Time</strong></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result[$i]!=NULL) 
	{
		$user_name = $users->getName($result[$i]['user_id']);
		
		$country_name = $ip_country->getCountryName($result[$i]['ip_address']);
	?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo  $user_name;?></td>
    <td valign="top" align="left"><?php echo $result[$i]['event'];?> </td>
    <td valign="top" align="left"><?php echo $result[$i]['ip_address'];?>&nbsp;(<?php echo $country_name;?>) </td>
    <td valign="top" align="left"><?php echo date("m/d/Y H:i:s", strtotime($result[$i]['db_add_date']));?> </td>
    
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
    	<input type="hidden" name="search_mode" 		value="SEARCH">
		<input type="hidden" name="user_list" 			value="<?=$_POST['user_list'];?>">
		<input type="hidden" name="pageNo" id="pageNo" 	value="<?=$_POST['pageNo']?>">
		<input type="hidden" name="pagePerNo" 			value="<?php echo $pagePerNo; ?>">
		<input type="hidden" name="url" 				value="view_log_details.php">
		<input type="hidden" name="from_date" 			value="<?php echo $from_date;?>">
		<input type="hidden" name="to_date" 			value="<?php echo $to_date;?>">
		<input type="hidden" name="hold_page" 			value="">
		<input type="hidden" name="fieldName" 			value="<?php echo $fieldName;?>">
		<input type="hidden" name="orderType" 			value="<?php echo $orderType;?>">
	</form>
<?php } ?>
<table><tr><td height="82px">&nbsp;</td></tr></table>
<?php } ?>