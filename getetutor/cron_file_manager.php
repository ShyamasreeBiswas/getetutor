<?php
ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

/* This is a Cron Job for deleting a file from the system */


include_once(PATH_TO_CLASS."class.file_manager.php");

$file_manager = new file_manager();

$file_name	= "";


$download_file_path = "D:/xampp/htdocs/getetutor/admin/download_files/"; 

$date	= date("Y-m-d");
$sql	= "SELECT * FROM file_manager WHERE file_unlink_date < '".$date."'";
$rs		= $file_manager->search($sql);
$sizeof	= sizeof($rs);

for($i=0;$i<$sizeof;$i++)
{
	$file_name = $rs[$i]['file_name'];
	$id = $rs[$i]['id'];
	
	if($id!="")
	{
		$file_path = $download_file_path.$file_name;
		if(file_exists($file_path))
		{
			unlink($file_path);
		}
	
		$file_manager->deleteFile($id);
	}
}

?>