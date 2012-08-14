<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS."class.database.php");
require_once(PATH_TO_CLASS."class.utility.php");
require_once("utils.php");

if($_SESSION['username']!='') {
	disphtml("main();");
} else {
	header('location: index.php');
	exit;
}
ob_end_flush();

function main(){
?>
<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
  <tr class="TDHEAD"> 
    <td>Administration Overview</td>
	</tr>
	<tr>
    <td class="text_normal_big">(Use the links on the left to perform Administrative Tasks)</td>
	</tr>
	</table><br>
	
<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
  <tr class="TDHEAD"> 
    <td colspan="2">Icon Reference</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/plus_icon.gif"></td>
		<td class="text_normal_big">This icon stands for "add". By clicking on this icon 
      a record can be added.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/edit_icon.gif"></td>
		<td class="text_normal_big">This icon stands for "edit". By clicking on this icon 
      a record can be edited.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/delete_icon.gif"></td>
		
    <td class="text_normal_big">This icon stands for "delete". By clicking on this 
      icon a record can be deleted.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/icon_reload.gif"></td>
		<td>This icon stands for "reload".  By clicking on this icon the page is reloaded or refreshed.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/unlock_icon.gif"></td>
		<td>This icon stands for "unlocked".  This means the record is active or in use. By clicking on this icon a record can be deactivated.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/locked_icon.gif"></td>
		<td>This icon stands for "locked".  This means the record is inactive. By clicking on this icon a record can be made active again.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/ok.gif"></td>
		<td>This icon stands for "featured".  This means the record is featured.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/notreplied_icon.gif"></td>
		<td>This icon stands for "not featured".  This means the record is not featured.</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/preview_icon.gif"></td>
	    <td class="text_normal_big">This icon stands for "preview".  By clicking on this icon the record can be viewed.</td>
	</tr>
	<!--<tr onMouseOver="this.bgColor='<?php //echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td><img border="0" src="images/print_icon.gif"></td>
	    <td class="text_normal_big">This icon stands for "print".  By clicking on this icon the records can be print.</td>
	</tr>-->
	</table><br>
<?php } ?>