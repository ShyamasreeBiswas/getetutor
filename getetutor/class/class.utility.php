<?
require_once(PATH_TO_CLASS."class.database.php");

class utility
{
	var $errors = '';
	
function __construct()
{
	$database = new database();
}
/*
@param $flname file name
@return image file name
*/
function reName($flname)
{
	if(preg_match("/#/",$flname))
	   $file_name=preg_replace("/#/","",$flname);
	else
	   $file_name=$flname;
	return $file_name;
}

function findexts($filename) 
{ 
	$filename 	= strtolower($filename) ; 
	$exts 		= split("[/\\.]", $filename) ; 
	 $n 		= count($exts)-1; 
	 $exts 		= $exts[$n]; 
	 return 	$exts; 
 } 
/******************************Function used for making thumbnail********************************/
function MakeThumbnail($Image_path, $flname, $d_path, $width, $height, $orginal="")
{
	$image_name = $flname;  //Image path retrived 
	$image_path = $Image_path;
	$d_path = $d_path;
	list($temp_width, $temp_height, $temp_type, $temp_attr) = getimagesize($Image_path.$flname);
	if($temp_width > $width)
	{
		$height = ceil(($temp_height*$width)/$temp_width);
	}
    if($temp_width <= $width)
	{
		$width = $temp_width;
		$height = $temp_height;
	}

	//Identifying Image type 
    $len = strlen($image_name); 
    $pos = strpos($image_name,"."); 
    $type = substr($image_name,$pos + 1,$len); 
	$type_new = strtolower($type);
    if ($type_new=="jpeg" || $type_new=="jpg") 
    { 
        self::thumb_jpeg ($image_name, $image_path, $d_path, $width, $height, $orginal); //Call to jpeg function 
    } 
    else if($type_new=="png" || $type_new=="PNG") 
    { 
        self::thumb_png ($image_name, $image_path, $d_path, $width, $height, $orginal);  //Call to PNG function 
    } 
	else if($type_new=="gif" || $type_new=="GIF") 
    { 
        self::thumb_gif ($image_name, $image_path, $d_path, $width, $height, $orginal);  //Call to PNG function 
    } 
}


//JPEG function 

function thumb_jpeg($image_name, $image_path, $d_path, $width, $height, $orginal="") 
{ 
    $source_path = $image_path; 
	if($orginal=="")
    	$destination_path = $image_path.$d_path;
	else 
    	$destination_path = $image_path.$orginal;
    $new_width=$width; 
    $new_height=$height; 
    $destimg=imagecreatetruecolor($new_width,$new_height) or die("Problem In Creating image"); 
    $srcimg=ImageCreateFromJPEG($source_path.$image_name) or die("Problem In opening Source Image"); 
    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing"); 
    ImageJPEG($destimg,$destination_path.$image_name) or die("Problem In saving");
    chmod($destination_path.$image_name,0777);
} 


//PNG function 

function thumb_png($image_name, $image_path, $d_path, $width, $height, $orginal="") 
{ 
    $source_path = $image_path; 
	if($orginal=="")
    	$destination_path = $image_path.$d_path;
	else 
    	$destination_path = $image_path.$orginal;
    $new_width=$width; 
    $new_height=$height; 
    $destimg=imagecreatetruecolor($new_width,$new_height) or die("Problem In Creating image"); 
    $srcimg=ImageCreateFromPNG($source_path.$image_name) or die("Problem In opening Source Image"); 
    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing"); 
    ImagePNG($destimg,$destination_path.$image_name) or die("Problem In saving"); 
	chmod($destination_path.$image_name,0777);
} 


//GIF function 

function thumb_gif($image_name, $image_path, $d_path, $width, $height, $orginal="") 
{ 
    $source_path = $image_path; 
	if($orginal=="")
    	$destination_path = $image_path.$d_path;
	else 
    	$destination_path = $image_path.$orginal;
    $new_width=$width; 
    $new_height=$height; 
    $destimg=imagecreatetruecolor($new_width,$new_height) or die("Problem In Creating image"); 
    $srcimg=ImageCreateFromGIF($source_path.$image_name) or die("Problem In opening Source Image"); 
    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing"); 
    ImageGIF($destimg,$destination_path.$image_name) or die("Problem In saving");
	chmod($destination_path.$image_name,0777);
} 

function thumbnail($filethumb, $file, $Twidth, $Theight, $tag)
{
	/*echo '<pre>';
	echo "<BR>@@@".$filethumb;
	echo "<BR>***".$file;
	echo "<BR>$$$".$Twidth;
	echo "<BR>###".$Theight;
	echo '</pre>';*/
	list($width,$height,$type,$attr)=getimagesize($file);
	/*echo '<pre>';
	echo "<BR>^^^".$width;
	echo "<BR>+++".$height;
	echo '</pre>';
	die();*/
	switch($type)
	{
		case 1:
			$img = ImageCreateFromGIF($file);
		break;
		case 2:
			$img = ImageCreateFromJPEG($file);
		break;
		case 3:
			$img = ImageCreateFromPNG($file);
		break;
	}
	if($tag == "width") //width contraint
	{
		$Theight=round(($height/$width)*$Twidth);
	}
	elseif($tag == "height") //height constraint
	{
		$Twidth=round(($width/$height)*$Theight);
	}
	else
	{
		if($width > $height)
			$Theight=round(($height/$width)*$Twidth);
		else
			$Twidth=round(($width/$height)*$Theight);
	}
	/*echo "<BR>^^^".$Twidth;
	echo "<BR>+++".$Theight;
	die();*/
	$thumb=imagecreatetruecolor($Twidth,$Theight);
	if(imagecopyresampled($thumb,$img,0,0,0,0,$Twidth,$Theight,$width,$height))
	{
		switch($type)
		{
			case 1:
				  ImageGIF($thumb,$filethumb);
			      break;
			case 2:
				  ImageJPEG($thumb,$filethumb);
			      break;
			case 3:
				  ImagePNG($thumb,$filethumb);
			      break;
		}
		chmod($filethumb,0777);
		return true;
	}
}
/*
@param $upload_dir Upload directory
@param $file_name File name
@param $tmp_name Temporary file name
@param $file_size File Size
@param $file_type File Type(Image or general file)
*/ 
function fileUpload($upload_dir, $file_name, $tmp_name, $file_size, $file_type, $sql="")
{
	
	if($file_name!="" && $file_size<2097152)
	{
		$upload_file=$upload_dir.$file_name;
		//echo $upload_file;
		//die();
		//chmod($upload_file,0777);
		
			switch($file_type)
			{
				case "image":
					$extn=strstr($file_name,".");
					$new_extn = strtoupper($extn);
					if($new_extn==".JPG" || $new_extn==".GIF" ||  $new_extn==".JPEG" ||  $new_extn==".PNG" || $new_extn==".BMP")
					{
						if(move_uploaded_file($tmp_name,$upload_file))
						{
							$this->errors = "Your file have uploaded successfully!!!";
							return true;
						}
						else
						{
							$this->errors = "A error occoured during uploading your file!!!";
							return false;
						}
					}
					else
					{
						$this->errors = "File should be an Image!!!";
						return false;
					}
				break;
				case "general":
					if(move_uploaded_file($tmp_name,$upload_file))
					{
						$this->errors = "Your file have uploaded successfully!!!";
						return true;
					}
					else
					{
						$this->errors = "A error occoured during uploading your file!!!";
						return false;
					}
				break;
			}//end of switch
		
	}//end of size and file checking
	else
	{
		$this->errors = "Your file is too big to upload!!!";
		return false;
	}		
	
}

/***************************** End of Functions used for making thumbnail*********************************/
/*
	Maintain image width, heaight
	@param $FLASH_WIDTH fixed width set to display
	@param $image_url full path of the image
*/
function maintainAspectRatio($FLASH_WIDTH,$image_url)
	{
	$arrImageSize = @getimagesize(trim($image_url),$arrImageSize);
	//print_r($arrImageSize);
	$width = $arrImageSize[0];
	$height = $arrImageSize[1];
	if($width > $FLASH_WIDTH )
		{
		$target_width = $FLASH_WIDTH;
		$target_height = round($height * ($FLASH_WIDTH/$width));
						
		}
	else
		{
		$target_width = $width;
		$target_height = $height;
		}
	return array("width"=>$target_width,"height"=>$target_height,"image_url"=>$image_url);
	}
// END method maintainAspectRatio() 

 
function DisplayAlphabet(){
		$str="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		$str=explode(",",$str);
		for($i=0; $i < sizeof($str); $i++){
			echo "<a href=\"#\" class='link' onClick=\"javascript:search_alpha('".$str[$i]."')\">$str[$i]</a>&nbsp;&nbsp;&nbsp;";
		}
}

function populateDropDown($sql, $elemName, $selValue, $keyVal, $showVal)
{
	//echo "<br/>selValue :".$selValue;
	$drpElem="<select name='".$elemName."' id='".$elemName."'>";
	//echo "<br/>drpElem :"."<select name='".$elemName."' id='".$elemName."'>";
	//die();
	   $run=mysql_query($sql);
	   while($data=mysql_fetch_array($run))
	   {
	   		//echo "<br/>keyVal :".$data[$keyVal];
			$select=$data['$keyVal']==$selValue ? ' selected' : '';
			$drpElem.="<option value='".$data[$keyVal]."' ".$select.">".$data[$showVal]."</option>";
		}
	$drpElem.="</select>";
	return $drpElem;
}

function comboPopulation($getSql, $selectedValue)
{	
   $combo_sql=$getSql;
   $combo_rs=mysql_query($combo_sql);	
   while($combo_rows=mysql_fetch_row($combo_rs))
   {   $strSelected = ""; 
   	   if($combo_rows[0]==$selectedValue)
   		{ $strSelected="SELECTED";}
 	  							   
	   $str.="<option value='".$combo_rows[0]."' ".$strSelected.">".stripslashes($combo_rows[1])."</option>";
   }
   echo $str;	   
   mysql_free_result($combo_rs);	
}



/*
	@param $var_date value of the date
*/		
function setDateFormat($var_date) ////////// set Date Format As 18 Jun 2008
{
	$sql="select date_format('".$var_date."','%d %b %Y')";
	if($rs=database::runQuery($sql))
		return $rs[0][0];
	else
	{
		$this->errors= "Error in Date Format.";
		return false;
	}
		
}
/*
	@param $var_date value of the date
*/		
function setDateTimeFormat($var_date_time) ////////// set Date Format As 15 Aug 2008 00:00:00
{
	$sql="select date_format('".$var_date_time."','%d %b %Y %T')";
	if($rs=database::runQuery($sql))
		return $rs[0][0];
	else
	{
		$this->errors= "Error in Date & Time Format.";
		return false;
	}
		
}
/*
	@param $number value of money
*/
function getMoneyFormat($var_num)
{
	setlocale(LC_MONETARY, 'en_US');
	return money_format('%.2n', $var_num) . "\n";
}

function pass($len=10){
$pass="";
$num = "0123456789abcdefghijklmnopqrstuvwxyz";
$i=0;
while($i < 10){
	$char =	substr($num,mt_rand(0,strlen($num)-1),1);
	$pass .= $char;
	$i++;
}
 return $pass;
}
/*
	@param $text String
	@param $limit 
*/
function getWords($text, $limit){
	$array = explode(" ",$text, $limit+1);
	if (count($array) > $limit)
	{
		unset($array[$limit]);
	}
	return implode(" ", $array);
}

/*
  Function used for pagination
  @param $count Total records count
  @param $frmName From Name in HTML
*/
function pagination($count,$frmName)
{
	if($_REQUEST['mode']=='delete')
	{
		$count=$count-1;
		$noOfPages = ceil($count/$GLOBALS['show']);
		$_REQUEST['pageNo']=$noOfPages;
	}
	else
	{
		$noOfPages = ceil($count/$GLOBALS['show']);
	}
?>
<script language="JavaScript">
<!--
function prevPage(no)
{
	document.<?=$frmName?>.action="<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>";
	document.<?=$frmName?>.pageNo.value = no-1;
	document.<?=$frmName?>.submit();
}
function nextPage(no)
{
	document.<?=$frmName?>.action="<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>";
	document.<?=$frmName?>.pageNo.value = no+1;
	document.<?=$frmName?>.submit();
}
function disPage(no)
{
	document.<?=$frmName?>.action="<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>";
	document.<?=$frmName?>.pageNo.value = no;
	document.<?=$frmName?>.submit();
}

//-->
</script>
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
	  <tr>
		<td width="15%" align="left">
		    <? if($_REQUEST[pageNo]!=1){ ?>
			    &#171;&nbsp;<a href="javascript:disPage(1)" style="text-decoration:none" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to this Page';" class="mainlink">First</a>
				<a href="javascript:prevPage(<?=$_REQUEST[pageNo] ?>);" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Previous Page';" class="mainlink">&#171; Prev</a>
			<? }else{ ?>
				<!--<a href="#" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Previous Page';"><font size="3">&#171;</font> Prev</a>-->
			<? }?>
		</td>
		<td align="center"><div align="center"><? ####### script to display no of pages #########
			//condition where no of pages is less than display limit
			$displayPageLmt = $GLOBALS['show']; #holds no of page links to display
			if($noOfPages <= $displayPageLmt){
				for($pgLink = 1; $pgLink <= $noOfPages; $pgLink++){
					if($pgLink==$_REQUEST[pageNo]){
						echo "<a href=\"#\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\" class=\"mainlink\">[$pgLink]</a>";
					}
					else{
						echo "<a href=\"javascript:disPage($pgLink)\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\" class=\"mainlink\">$pgLink</a>";
					}	
					if($pgLink<>$noOfPages) echo "&nbsp;|&nbsp;";
				} #end of for loop
			} #end of if
			//condition for no of pages greater than display limit
			if($noOfPages > $displayPageLmt){
				if(($_REQUEST[pageNo]+($displayPageLmt-1)) <= $noOfPages){
					for($pgLink = $_REQUEST[pageNo]; $pgLink <= ($_REQUEST[pageNo]+$displayPageLmt-1); $pgLink++){
						if($pgLink==$_REQUEST[pageNo]){
							echo "<a href=\"#\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\" class=\"mainlink\">[$pgLink]</a>";
						}
						else{
							echo "<a href=\"javascript:disPage($pgLink)\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\" class=\"mainlink\">$pgLink</a>";
						}
						if($pgLink<>($_REQUEST[pageNo]+$displayPageLmt-1)) echo "&nbsp;|&nbsp;";
					}#end of for loop						
				}#end of inner if
				else{
					for($pgLink = ($noOfPages - ($displayPageLmt-1)); $pgLink <= $noOfPages; $pgLink++){
						if($pgLink==$_REQUEST[pageNo]){
							echo "<a href=\"#\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\" class=\"mainlink\">[$pgLink]</a>";
						}
						else{
							echo "<a href=\"javascript:disPage($pgLink)\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\" class=\"mainlink\">$pgLink</a>";
						}
						if($pgLink<>$noOfPages) echo "&nbsp;|&nbsp;";
					}#end of for loop
				}					
			}#end of if noOfPage>displayPageLmt
		?></div></td>
		<td width="15%" align="right">
		  <div align="right">
		    <? if($_REQUEST[pageNo] != $noOfPages) { ?>
				<a href="javascript:nextPage(<?=$_REQUEST[pageNo];?>)" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Next Page';" class="mainlink">Next &#187;</a>
				<a href="javascript:disPage(<?=$noOfPages;?>)" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Next Page';" class="mainlink">Last &#187;</a>
			<? }else{ ?>
				<!--<a href="#" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Next Page';">Next <font size="3">&#187;</font></a>-->
			<? }?>
		 </div>
		</td>
	  </tr>
	  <? if($noOfPages > 1){ ?>
	  <tr>
		<td colspan="3" align="center" class="txtlink3">
			<div align="center">Page no. :
				 <select onchange="javascript:disPage(this.value);" style="font-family:verdana; font-size:11px">
					<? for($i=1;$i<=$noOfPages;$i++){?>
						<option value="<?=$i;?>"<?=($_REQUEST[pageNo]==$i)?"selected":"";?>><?=$i;?></option>
					<? }?>
				</select>
			</div>
		</td>	
	  </tr>
	  <? } ?>
	</table>
<?
} ///////// End of function pagination()

/*
	Set result per page Use onchange method
	@param $x First vale of dropdown
	@param $y Second Value of dropdown
	@param $z Third value of dropdown
	@param $frmName From name input type submit
*/
function ComboResultPerPage($x,$y,$z,$a,$frmName)
{
?>

<?
 //$this->x=$x;
 //$this->y=$y;
 //$this->z=$z;
  $r=$GLOBALS['show'];
  $queryString=basename($_SERVER['PHP_SELF'])."?".$_SERVER["QUERY_STRING"];
  echo " <script language='JavaScript'>
	 function disPerPage(no)
	 {
	 		document.$frmName.action='$queryString';
     		document.$frmName.pagePerNo.value = no;
	
	 document.$frmName.submit();
	 
}</script>";
  $strComboResultPerPage=" <select name='prof_no' onChange='disPerPage(this.value)'><option value='$x'";
		 if($r==$x) 
		   $strComboResultPerPage.= "selected";
		 $strComboResultPerPage.=">$x</option>";
         $strComboResultPerPage.="<option value='$y'";
		 
         if($r==$y) 
		   $strComboResultPerPage.= "selected";
		 $strComboResultPerPage.=">$y</option>";
         $strComboResultPerPage.="<option value='$z' ";
		 
		 if($r==$z) 
		   $strComboResultPerPage.= "selected";
		 $strComboResultPerPage.=">$z</option>";
         $strComboResultPerPage.="<option value='$a' ";
		 
		 if($r==$a) 
		  $strComboResultPerPage.="selected";

         $strComboResultPerPage.=">$a</option></select>";
		 
		return $strComboResultPerPage;
} ///////// End of function ComboResultPerPage()
/*
	Display Date Month Day Year
	@param $name
	@param $selected
*/
function dispDates($name,$selected){
	list($secName,) = explode("_",$name);
	$day=date('d',strtotime($selected));$month=date('m',strtotime($selected));$year=date('Y',strtotime($selected));
	$month_arr=array('January','February','March','April','May','June','July','August','September','October','November','December');?>
	<select name="<?=$name;?>_month" <? if($name == $secName."_start"){ echo " onChange='document.forms[1].".$secName."_end_month.selectedIndex=this.selectedIndex'";}?>>
	<? for($i=1;$i<13;$i++){
		$i=$i<10?$i='0'.$i:$i;?>
		<option value="<?=$i;?>" <?=$i==$month?"selected":"";?>><?=$month_arr[$i-1];?></option>
	<? }?>
	</select>&nbsp;<select name="<?=$name;?>_day" <? if($name == $secName."_start"){ echo " onChange='document.forms[1].".$secName."_end_day.selectedIndex=this.selectedIndex'";}?>>
	<? for($i=1;$i<32;$i++){
		$i=$i<10?$i='0'.$i:$i;?>
		<option value="<?=$i;?>" <?=$day==$i?"selected":"";?>><?=$i;?></option>
	<? }?>
	</select>&nbsp;<select name="<?=$name;?>_year" <? if($name == $secName."_start"){ echo " onChange='document.forms[1].".$secName."_end_year.selectedIndex=this.selectedIndex'";}?>>
	<? for($i=0;$i<7;$i++){?>
		<option value="<?=date('Y')+$i;?>" <?=(date('Y')+$i)==$year?"selected":"";?>><?=date('Y')+$i;?></option>
	<? }?>
	</select>
<? }

function leftMenu()
{
	
?>
<script type="text/javascript">
		d = new dTree('d');
	    
		d.add(0,-1,'<B>&nbsp;Control Panel</B>');
		<? 
		$where = " ";
		if($_SESSION['type']=='SUB') 
		{
			$menu = database::runQuery("select * from menu where parent_id='0' and is_active='Y' and (sub_admin = '0,1' || sub_admin = '0,1,2') order by menu_name");
		} 
		else if($_SESSION['type']=='SUBS') 
		{
			$menu = database::runQuery("select * from menu where parent_id='0' and is_active='Y' and (sub_admin = '0,2' || sub_admin = '0,1,2') order by menu_name");
		} else
		{
			$menu = database::runQuery("select * from menu where parent_id='0' and is_active='Y' order by menu_name");
		}
		$i=0;
		while($menu[$i]!=NULL)
		{
		
		?> 
			d.add(<?=$menu[$i]['menu_id'];?>,<?=$menu[$i]['parent_id']?>,'<B>&nbsp;<?=$menu[$i]['menu_name']?></B>');
		<?
		if($_SESSION['type']=='SUB')
		{
			$sub_menu=database::runQuery("select * from menu where parent_id='".$menu[$i]['menu_id']."' and is_active='Y' and (sub_admin = '0,1' || sub_admin = '0,1,2') ".$where."  order by menu_name");
		} else if($_SESSION['type']=='SUBS')
		{
			$sub_menu=database::runQuery("select * from menu where parent_id='".$menu[$i]['menu_id']."' and is_active='Y' and (sub_admin = '0,2' || sub_admin = '0,1,2') ".$where."  order by menu_name");
		} 
		else 
		{
			$sub_menu=database::runQuery("select * from menu where parent_id='".$menu[$i]['menu_id']."' and is_active='Y' order by menu_name");
		}
		$j=0;
		$k=10;// Donot chage this value
	
		while($sub_menu[$j]!=NULL)
		{
		?>
			d.add(<?=$sub_menu[$j]['parent_id'].$k;?>,<?=$sub_menu[$j]['parent_id'];?>,'<?=$sub_menu[$j]['menu_name']?>','<?=$sub_menu[$j]['page_name']?>');
		<?
		$k++;
		$j++;
		}
		$i++;
		}
		?>
					
		document.write(d);
		
	</script>

<?
}
function cleanData($data) 
{
	return trim(stripslashes($data));
}

function prepareData($data) 
{
	return trim(addslashes($data));
}

function __destruct()
{
}
}//// End of class utility
?>
