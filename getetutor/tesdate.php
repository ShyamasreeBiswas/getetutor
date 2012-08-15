<script language="JavaScript">


function RdeleteData(id)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true ) {
		document.frm_req.mode.value='delete';
		document.frm_req.id.value=id;
		document.frm_req.submit();
	}
}
</script>

<?php 

	$sqlschd = "select * from schedules";
	$rowschd = mysql_query($sqlschd);
	$cnr = 0;
	$arr_dt = array();
	while($resschd = mysql_fetch_array($rowschd)){
		
		$sdt_arr = explode("/", $resschd['sdate']);
		$sdtnew = $sdt_arr[2]."-".$sdt_arr[0]."-".$sdt_arr[1];
		
		$todays_date = date("Y-m-d");
		
		$today = strtotime($todays_date);
		$check_date = strtotime($sdtnew);
		
		if ($check_date > $today) {
			
			$arr_dt[$cnr]['sdatenew'] = $resschd['sdate'];
			$arr_dt[$cnr]['schid'] = $resschd['id'];
		}
		
		$cnr++;
	}
	
	echo "<pre>";
	print_r($arr_dt); 
	echo count($arr_dt); 
	die;
	
	if($_SESSION['type']= 'SUP') {
		$sql_req = "select * from schedules";
	}else {
		$sql_req = "select * from schedule_request where tutor_id = '".$_SESSION['id']."' group by schedule_id";
	}
	$row_req = mysql_query($sql_req);	

?>
	
<table width="98%" align="center" border="0" cellpadding="5" cellspacing="2"  class="border">
  <tr class="TDHEAD"> 
    <td colspan="9">Manage Requests</td>
  </tr>
  <tr class="text_normal" bgcolor="#E7E7F7"> 
    <td width="10%" align="center" ><div align="center"><strong>SL</strong></div></td>
    <td width="22%" ><strong>Student Name</strong></td>
	<td width="30%" ><strong>Student Email</strong></td>
    <td width="33%" ><strong>Date</strong></td>
    <td width="33%" ><strong>Time</strong></td>
    <td width="33%" ><strong>For Course</strong></td>
    <td width="5%"  ><div align="center"><strong>Approve</strong></div></td>
    <td width="5%"  ><div align="center"><strong>Delete</strong></div></td>
  </tr>
  <?php
	$i=0;
	$cnt=$GLOBALS[start]+1;
	while($result_req[$i]!=NULL) {
		
	?>
  <tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''"> 
    <td valign="top" align="center"><?php echo $cnt++;?> </td>
    <td valign="top" align="left"><?php echo $result[$i]['sdate'];?></td>
    <td valign="top" align="left"><?php echo $result[$i]['day'];?> </td>
    <td valign="top" align="left"><?php echo $result[$i]['stime'];?> </td>
    <td valign="top" align="center"><a href="javascript:ChangeStatus(<?php echo $result[$i]['id'];?>,<?php echo $GLOBALS['start']; ?>)" title="<?php echo ($result[$i]['status']=='Y')?'Turn off':'Turn on'; ?>"> 
      <?php echo $result[$i]['status']=='N' ? "<font color=\"#FF0000\"><b>Inactive</b></font>" : "<font color=\"green\"><b>Active</b></font>"; ?> 
      </a></td>
    <td align="center" valign="top"><a href="javascript:viewData( <?php echo $result[$i]['id'];?>);" title="View Details"><img src="images/preview_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:editData( <?php echo $result[$i]['id'];?>);" title="Edit Schedule"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center" valign="top"><a href="javascript:deleteData( <?php echo $result[$i]['id'];?>);" title="Delete Schedule"><img name="xx" src="images/delete_icon.gif" border="0"></a></td>
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