<?php

ob_start();
define("ROOT_PATH","");
define("INCLUDES_PATH", ROOT_PATH."includes/");
define("PATH_TO_CLASS", ROOT_PATH."class/");

require_once(PATH_TO_CLASS.'class.ip_country.php');	
$ip_country 	= new ip_country();

mysql_connect(DB_HOST, DB_USER, DB_PWD);
mysql_select_db(DB_NAME);

$user_id	= $_POST['user_list'];	
$from_date	= date("Y-m-d", strtotime($_POST['from_date']));	
$to_date	= date("Y-m-d", strtotime($_POST['to_date']));	
$pageNo		= $_POST['pageNo'];	

$where  = " WHERE LD.user_id !=1 AND UM.name!='admin'";
$where .= " AND date(LD.db_add_date) between '".$from_date."' AND '".$to_date."'";

if($user_id>0)
{
	$where .= " AND LD.user_id = ".$user_id;
}

$limit = " ";
if($pageNo>1)
{
	$lower_limit = ($pageNo*100)-100;
	$limit = " LIMIT ".$lower_limit.", 100"; 
}
else
{
	$limit = " LIMIT 0, 100"; 
}


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=log_report.csv');
	
// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
	
// output the column headings
fputcsv($output, array('Name', 'Event Description', 'IP Address', 'Date & Time', 'Country'));


$sql = "select UM.name, LD.event, LD.ip_address, LD.db_add_date FROM log_details AS LD INNER JOIN user_master AS UM ON LD.user_id = UM.id ".$where.$limit;
	
$rows = mysql_query($sql);

// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) 
{
	fputcsv($output, $row);
	$country_name = $ip_country->getCountryName($result[$i]['ip_address']);
	fputcsv($output, $country_name);
}

?>