<?php

ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(ROOT_PATH."utils.php");
include_once(PATH_TO_CLASS."class.utility.php");
require_once(PATH_TO_CLASS.'class.log.php');
include_once(PATH_TO_CLASS."class.getetutor.php");
include_once(PATH_TO_CLASS."class.users.php");


$user_id 		= $_GET['user_id'];
$from_date 		= $_GET['from_date'];
$to_date  		= $_GET['to_date'];

//$leads = new leads();
$users = new users();
$log_details= new log_details();


mysql_connect(DB_HOST, DB_USER, DB_PWD);
mysql_select_db(DB_NAME);

$area_code_exploded_array 	 = explode(",", $area_code_array);
$no_area_code_exploded_array = explode(",", $no_area_code_array);

$user_name			= $users->getName($_SESSION['id']);

$ourFileName = "log_report.csv";
$ourFileHandle = fopen($ourFileName, 'w+') or die("can't open file");

// output the column headings
fputcsv($ourFileHandle, array('Name', 'Event Description', 'IP Address (Country)', 'Date & Time'));

$rows = mysql_query("SELECT * FROM log_details WHERE user_id !=1 AND date(db_add_date) between '".$from_date."' AND '".$to_date."' AND user_id = ".$user_id);
						 
// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) 
{
	fputcsv($ourFileHandle, $row);
}	
	
fclose($ourFileHandle);

echo $ourFileHandle;
?>