<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS."class.database.php");
require_once(PATH_TO_CLASS."class.utility.php");
include_once(PATH_TO_CLASS."class.studymates.php");
require_once("utils.php");

$utility 	= new utility();

$vid 	= $utility->cleanData($_REQUEST['vid']);
$ftype 	= $utility->cleanData($_REQUEST['ftype']);

if($_SESSION['username']!='') {
	disphtml("main(".$vid.", ".$ftype.");");
} else {
	header('location: index.php');
	exit;
}
ob_end_flush();

function main($vid, $ftype){
	
	$studymates 	= new studymates();
	$mate_name = $studymates->getMateName($vid);
	$mate_type = $studymates->getMateType($vid);
	
?>
	
<table width="98%" align="center" border="0" class="border" cellpadding="5" cellspacing="1">
  <tr class="TDHEAD"> 
    <td colspan="2">Video</td>
	</tr>
	<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR; ?>'" onMouseOut="this.bgColor=''">
		<td>
        <?php if($ftype == 'video') {?>
        <object type="<?php echo $mate_type;?>" data="uploaded_files/<?php echo $mate_name;?>" width="320" height="255">
          <param name="src" value="uploaded_files/<?php echo $mate_name;?>">
          <param name="autoStart" value="1">  
		</object>
        <?php }else if($ftype == 'image') {?>
            <img src="uploaded_files/<?php echo $mate_name;?>" />   
   	 	<?php }?>
        </td>
	</tr>	
	
	</table><br>
<?php } ?>